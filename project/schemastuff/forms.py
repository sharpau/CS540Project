from django import forms

class BusinessForm(forms.Form):
    Business = forms.CharField(max_length=100)