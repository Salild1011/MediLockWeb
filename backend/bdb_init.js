const BigchainDB = require('bigchaindb-driver')
const bip39 = require('bip39')

const API_PATH = 'https://test.bigchaindb.com/api/v1/'

module.exports = function() {
    this.driver = require('bigchaindb-driver');
    this.conn = new BigchainDB.Connection(API_PATH, {
        app_id: '04c40cd7',
        app_key: 'a9c340639bbcbe33ad68534304478db9'
    });
}
