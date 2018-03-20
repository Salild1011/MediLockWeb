var WebSocketServer = require('websocket').server;
var http = require('http');

var server = http.createServer(function(request, response) {
  // process HTTP request. Since we're writing just WebSockets
  // server we don't have to implement anything.
});
server.listen(1355, function() { });

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

      var idJson = JSON.parse(message.utf8Data);
      var id = idJson.id;
      console.log(message);

      var MongoClient = require('mongodb').MongoClient;
      var url = "mongodb://localhost:27017/";
      var hash = "";

      MongoClient.connect(url, function(err, db) {
        if (err) throw err;

        var dbo = db.db("medilock");
        dbo.collection("patients").findOne({id: id}, function(err, res) {
          if (err) throw err;
          console.log(res);
          hash = res.hash;
          console.log(res.hash);
          console.log("Hash---" + hash);
          connection.sendUTF(JSON.stringify({ hash: hash }));

          db.close();
        });
      });
    }
  });

  connection.on('close', function(connection) {
    console.log("close");
  });
});
