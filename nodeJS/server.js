var express = require('express'),
		session = require('express-session'),
		app = express(),
		path = require('path');

app.use(session({
	secret: '1234567890QWERTY',
	resave: true,
	saveUninitialized: false
}));
const axios = require('axios');
var ClientOAuth2 = require('client-oauth2')
require('dotenv').config()

var auth = new ClientOAuth2({
  clientId: process.env.CLIENT_ID,
  clientSecret: process.env.CLIENT_SECRET,
  accessTokenUri: process.env.ACCESS_TOKEN_URI,
  authorizationUri: process.env.AUTHORIZATION_URI,
  redirectUri: process.env.REDIRECT_URI
})

app.set('view engine', 'ejs');

app.get('/callback', function (req, res) {

	auth.code.getToken(req.originalUrl) .then(function (user) {
		// Refresh the current users access token.
		user.refresh().then(function (updatedUser) {
		  req.session.token = updatedUser.accessToken;
		  res.redirect('/request');
		})
	})
});

app.post('/request', function(req, res){
	var token = req.session.token;
	if (token)
	{
		axios.get("https://api.intra.42.fr/v2/users", {
			headers: { 'Authorization': 'Bearer ' + token }
		}).then(function (response) {
			console.log(response.data);
		})
		.catch(function (error) {
			res.render(path.join(__dirname + '/request.ejs'), {me: '', req_ret: response.data});
		});
	}
});

app.get('/request', function (req, res) {
	if (!req.session.token)
		res.redirect('/');
	else
	{
		var token = req.session.token;
		axios.get("https://api.intra.42.fr/v2/me", {
			headers: {
			  'Authorization': 'Bearer ' + token
			}
		  }).then(function (response) {
			res.render(path.join(__dirname + '/request.ejs'), {me: response.data, req_ret: ''});
		  })
		  .catch(function (error) {
			res.render(path.join(__dirname + '/request.ejs'), {me: 'Bad request.', req_ret: ''});
		  });
	}
});

app.get('/', function (req, res) {
	res.render(path.join(__dirname + '/index.ejs'));
});

app.listen(3000);