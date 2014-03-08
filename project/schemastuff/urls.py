from django.conf.urls import patterns, url

from schemastuff import views

urlpatterns = patterns('',
    url(r'^$', views.index, name='index'),
)