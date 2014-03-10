from django.shortcuts import get_object_or_404, render
from django.http import HttpResponseRedirect
from django.core.urlresolvers import reverse
from django.views import generic
from django.http import HttpResponse

from schemastuff.models import businesses, reviews, users, tips

from forms import BusinessForm

def index(request):
    if request.method == 'post':
        form = BusinessForm(request.POST)
    else:
        form = BusinessForm()
    return render(request, 'schemastuff/index.html', {
        'form': form,
    })
	
def business_list(request):
    list = businesses.objects.raw("SELECT * FROM schemastuff_businesses where name like %s", ["%" + request.POST['Business'] + "%" ])
    context = {'list': list}
    return render(request, 'schemastuff/business_list.html', context)
	
def detail(request, businesses_id):
        poll = schemastuff.objects.get(pk=businesses_id)
    return render(request, 'polls/detail.html', {'poll': poll})