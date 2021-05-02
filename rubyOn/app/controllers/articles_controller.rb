require 'oauth2'
require 'dotenv'

class ArticlesController < ApplicationController
  def index
    @articles = Article.all
  end
end


class ArticlesController < ApplicationController
  def callback
    code = request.params["code"]
    client = OAuth2::Client.new(ENV['CLIENT_ID'], ENV['CLIENT_SECRET'], site: 'https://api.intra.42.fr')
    client.auth_code.authorize_url(redirect_uri: ENV['AUTHORIZATION_URI'])
    begin
      token = client.auth_code.get_token(request.params["code"], redirect_uri: ENV['REDIRECT_URI'], headers: {'Authorization' => 'Bearer ' + request.params["code"]})
      @me = token.get('/v2/me', params: {'query_foo' => 'bar'}).parsed
    rescue Exception
      redirect_to "/"
      @em = "Expired token Login again"
    end
  end
end