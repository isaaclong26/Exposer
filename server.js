
const express = require('express');
const path = require('path');
const { exec } = require("child_process");

const { dirname } = require('path');
const PORT = process.env.PORT || 3001;

const app = express();

// Sets up the Express app to handle data parsing
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public/index.html'));
  });





app.listen(PORT, () => {
        console.log(`Example app listening at http://localhost:${PORT}`);
      })