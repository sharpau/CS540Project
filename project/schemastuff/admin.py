from django.contrib import admin

from schemastuff.models import businesses, users, reviews, tips

admin.site.register(businesses)
admin.site.register(users)
admin.site.register(reviews)
admin.site.register(tips)