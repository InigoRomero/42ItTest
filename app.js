var express = require('express'),
		session = require('express-session'),
		app = express(),
		path = require('path'),
		bodyParser = require('body-parser');

app.use(session({
	secret: '1234567890QWERTY',
	resave: true,
	saveUninitialized: false
}));
const request = require('request');
const axios = require('axios');
var ClientOAuth2 = require('client-oauth2')
 
var auth = new ClientOAuth2({
  clientId: '3390c897e9313d75feb7518f9aa8ea1024e200d81915588048d7b337f9758f57',
  clientSecret: '34d02aeef7392d0d8adac92e29dc7631641ec3095539313691b6c3a52d58b259',
  accessTokenUri: 'https://api.intra.42.fr/oauth/token',
  authorizationUri: 'https://api.intra.42.fr/oauth',
  redirectUri: 'http://localhost:3000/callback'
})

app.set('view engine', 'ejs');

app.get('/callback', function (req, res) {

	auth.code.getToken(req.originalUrl) .then(function (user) {
		// Refresh the current users access token.
		user.refresh().then(function (updatedUser) {
			console.log(updatedUser.accessToken)
		  req.session.token = updatedUser.accessToken;
		  res.redirect('/request');
		})
	})
});

app.use(bodyParser.urlencoded({
    extended: true
}));

app.post('/request', function(req, res){

	console.log(req.body.request);
	var token = req.session.token;
	if (token)
	{
		auth.oauth2.api('GET', req.body.request, {
			access_token: token.token.access_token
		}, function (err, data) {
			if (err) {
				console.log("Bad request.")
				res.render(path.join(__dirname + '/request.ejs'), {req_ret: 'Bad request.'});
			} else {
				res.render(path.join(__dirname + '/request.ejs'), {req_ret: JSON.stringify(data)});
			}
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
		/*request({
			url: 'https://api.intra.42.fr/v2/me',
			headers: {
			   'Authorization': 'Bearer ' + token
			},
			rejectUnauthorized: false
		  }, function(err, resp) {
				if(err) {
					res.render(path.join(__dirname + '/request.ejs'), {me: 'Bad request.', req_ret: ''});
				} else {
					console.log(resp.body);
					res.render(path.join(__dirname + '/request.ejs'), {me: resp.body, req_ret: ''});
				}
		  
		  });*/
		/*auth.code.request('GET', "/v2/me", {
			access_token: token.access_token
		}, function (err, data) {
			if (err) {
				console.log("Bad request.")
				res.render(path.join(__dirname + '/request.ejs'), {me: 'Bad request.', req_ret: ''});
			} else {
				res.render(path.join(__dirname + '/request.ejs'), {me: data, req_ret: ''});
			}
		});*/
	}
});

app.get('/', function (req, res) {

	var token = req.session.token;
	if (token && token.expired()) {
		token.refresh(function(err, res) {
			if (err) {
				token = -1;
			} else {
				token = res;
			}
		})
	}
	res.render(path.join(__dirname + '/index.ejs'));
});

app.listen(3000);
