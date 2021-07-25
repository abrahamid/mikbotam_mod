#!/usr/bin/env
const fs = require('fs');
const express = require('express');
const qrcode = require('qrcode-terminal');
const http = require('http');
const { Client } = require('whatsapp-web.js');
const { response } = require('express');

const app = express();

app.use(express.json());
app.use(express.urlencoded({extended: true}));

const SESSION_FILE_PATH = './session.json';
let sessionCfg;
if (fs.existsSync(SESSION_FILE_PATH)) {
    sessionCfg = require(SESSION_FILE_PATH);
}


const client = new Client({
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-extensions'
        ]
    },
    session: sessionCfg
});

client.on('qr', (qr) => {
    // Generate and scan this code with your phone
    qrcode.generate(qr, {small: true});
});

client.on('authenticated', (session) => {
    console.log('AUTHENTICATED', session);
    sessionCfg=session;
    fs.writeFile(SESSION_FILE_PATH, JSON.stringify(session), function (err) {
        if (err) {
            console.error(err);
        }
    });
});

client.on('auth_failure', msg => {
    // Fired if session restore was unsuccessfull
    console.error('AUTHENTICATION FAILURE', msg);
});
client.on('ready', () => {
    console.log('Client is ready!');
});

// client.on('message', msg => {
//     if (msg.body == '!ping') {
//         client.sendMessage(msg.from, 'pong');
//     }else if(msg.body == 'hai'){
//         msg.reply('halo');
//     }else if(msg.body == 'info'){
//         msg.reply('saya adalah bot yang dibuat untuk memudahkan anda dalam menggunakan wifi\nGunakan internet dengan bijak')
//     }else if(msg.body == '%'){
//         msg.reply('saya tidak paham');
//     }else if(msg.body == 'id'){
//         client.sendMessage(msg.from,msg.from);
//     }
// });

client.on('message', msg => {
    var number = msg.from;
    var message = msg.body;
    var request = require("request");

    var options = { method: 'POST',
      url: 'http://localhost/whatsapp/webhook.php',
      headers: 
       { 'postman-token': 'df2461d3-65aa-1d08-f5bc-fc4eba5fc029',
         'cache-control': 'no-cache',
         'content-type': 'application/json' },
      body: { number: number, message: message },
      json: true };
  
    request(options, function (error, response, body) {
      if (error) throw new Error(error);
  
      console.log(body);
    });

});

client.initialize();

//send message api

app.post('/sendMessage', (req,res)=>{
    const number = req.body.number;
    const message = req.body.message;

    client.sendMessage(number, message).then(response => {
        res.status(200).json({
            status: true,
            response: response
        });
    }).catch(err => {
        res.status(500).json({
            status: false,
            response: err
        });
    });
    console.log(req.body);
});



app.listen(8081, function(){
    console.log('App running on *:'  + 8081);
});
