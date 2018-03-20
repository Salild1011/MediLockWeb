var WebSocketServer = require('websocket').server;
var http = require('http');
var init = require('./bdb_init.js')();

var server = http.createServer(function(request, response) {
  // process HTTP request. Since we're writing just WebSockets
  // server we don't have to implement anything.
});
server.listen(1340, function() { });

// create the server
wsServer = new WebSocketServer({
  httpServer: server
});

// WebSocket server
wsServer.on('request', function(request) {
  var connection = request.accept(null, request.origin);
  console.log("Request");

  // This is the most important callback for us, we'll handle
  // all messages from users here.
  connection.on('message', function(message) {
    if (message.type === 'utf8') {
      var patient_json = JSON.parse(message.utf8Data);
      console.log(patient_json);

      const user = new driver.Ed25519Keypair()
      const tx = driver.Transaction.makeCreateTransaction(
          { patient_json },
          null,
          [ driver.Transaction.makeOutput(driver.Transaction.makeEd25519Condition(user.publicKey))],
          user.publicKey)
      const txSigned = driver.Transaction.signTransaction(tx, user.privateKey)

      conn.postTransaction(txSigned)

      console.log(tx.id);

      var MongoClient = require('mongodb').MongoClient;
      var url = "mongodb://localhost:27017/";

      MongoClient.connect(url, function(err, db) {
        if (err) throw err;

        var dbo = db.db("medilock");
        var myobj = { id: patient_json.id, hash: tx.id };
        dbo.collection("patients").insertOne(myobj, function(err, res) {
          if (err) throw err;
          console.log("1 document inserted");
          db.close();
        });
      });
    }
  });

  connection.on('close', function(connection) {
    console.log("close");
  });
});
