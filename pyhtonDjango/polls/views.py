# Create your views here.
from django.http import HttpResponse
from django.template import loader
from django.http import Http404
from django.shortcuts import render
from .models import Question
from django.conf import settings
import requests

from oauth2_client.credentials_manager import CredentialManager, ServiceInformation, OAuthError
from oauth2_client.http_server import read_request_parameters, _ReuseAddressTcpServer

def index(request):
    latest_question_list = Question.objects.order_by('-pub_date')[:5]
    context = {'latest_question_list': latest_question_list}
    return render(request, 'polls/index.html', context)
    
def detail(request):
    code = request.GET["code"]
    scopes = ['scope_1', 'scope_2']
    service_information = ServiceInformation(settings.AUTHORIZATION_URI,
                                            settings.ACCESS_TOKEN_URI,
                                            settings.CLIENT_ID,
                                            settings.CLIENT_SECRET,
                                            scopes)
    manager = CredentialManager(service_information)
    redirect_uri = settings.REDIRECT_URI
    manager.init_with_authorize_code(redirect_uri, code)
    token = manager._access_token
    #call to api with token
    endpoint = "https://api.intra.42.fr/v2/me"
    data = {"ip": "1.1.2.3"}
    headers = {"Authorization": "Bearer " + token}

    me = requests.get(endpoint, data=data, headers=headers).json()
    return render(request, 'polls/detail.html', {'me': me})
    