/*jshint esversion: 6 */

require('./bootstrap')

window.Vue = require('vue')

const app = new Vue({
    el: '#app',
    data: {
        showSuccess: false,
        successMessage: '',
        gameEnded: false,
        board: [0, 0, 0, 0, 0, 0, 0, 0, 0]
    },
    methods: {
        // ----------------------------------------------------------------------------------------
        // GAME LOGIC - START
        // ----------------------------------------------------------------------------------------
        hasRow: function (value) {
            return ((this.board[0] == value) && (this.board[1] == value) && (this.board[2] == value)) ||
                ((this.board[3] == value) && (this.board[4] == value) && (this.board[5] == value)) ||
                ((this.board[6] == value) && (this.board[7] == value) && (this.board[8] == value)) ||
                ((this.board[0] == value) && (this.board[3] == value) && (this.board[6] == value)) ||
                ((this.board[1] == value) && (this.board[4] == value) && (this.board[7] == value)) ||
                ((this.board[2] == value) && (this.board[5] == value) && (this.board[8] == value)) ||
                ((this.board[0] == value) && (this.board[4] == value) && (this.board[8] == value)) ||
                ((this.board[2] == value) && (this.board[4] == value) && (this.board[6] == value))
        },
        checkGameEnded: function () {
            if (this.hasRow(1)) {
                this.successMessage = 'Player 1 won the Game'
                this.showSuccess = true
                this.gameEnded = true
            }
            if (this.hasRow(2)) {
                this.successMessage = 'Player 2 won the Game'
                this.showSuccess = true
                this.gameEnded = true
            }
            if (this.isBoardComplete()) {
                this.successMessage = 'The Game ended in a Tie'
                this.showSuccess = true
                this.gameEnded = true
            }
            return false
        },
        isBoardComplete: function () {
            var returnValue = true
            this.board.forEach(function (element) {
                if (element === 0) {
                    returnValue = false
                    return
                }
            })
            return returnValue
        }
        // ----------------------------------------------------------------------------------------
        // GAME LOGIC - END
        // ----------------------------------------------------------------------------------------
    },
    computed: {

    }
})
