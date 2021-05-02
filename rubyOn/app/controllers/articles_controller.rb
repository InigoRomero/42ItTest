require 'oauth2'

class ArticlesController < ApplicationController
  def index
    @articles = Article.all
  end
end


class ArticlesController < ApplicationController
  def callback
    code = request.params["code"]
    client = OAuth2::Client.new('3390c897e9313d75feb7518f9aa8ea1024e200d81915588048d7b337f9758f57', '34d02aeef7392d0d8adac92e29dc7631641ec3095539313691b6c3a52d58b259', site: 'https://api.intra.42.fr')
    client.auth_code.authorize_url(redirect_uri: 'https://api.intra.42.fr/oauth')
    token = client.auth_code.get_token(request.params["code"], redirect_uri: 'http://localhost:3000/callback', headers: {'Authorization' => 'Bearer ' + request.params["code"]})
    @response = token.get('/v2/me', params: {'query_foo' => 'bar'}).parsed

    #auth_url = client.implicit.authorization_path(:redirect_uri => 'https://api.intra.42.fr/oauth')
   # token_url = client.authorization_code.token_path(
   # :code => aXW2c6bYz, 
   # :redirect_uri => 'http://localhost/oauth/token')
   #authorizationUri
  end
end