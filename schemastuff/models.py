from django.db import models

class businesses(models.Model):
	business_id = models.CharField(max_length=200)
	name = models.CharField(max_length=200)
	stars = models.DecimalField(max_digits=5, decimal_places=4)
	checkins = models.CharField(max_length=200)
	
class reviews(models.Model):
	business_id = models.CharField(max_length=200)
	user_id = models.CharField(max_length=200)
	stars = models.DecimalField(max_digits=5, decimal_places=4)
	content = models.CharField(max_length=200)
	date = models.CharField(max_length=200)
	funny_votes = models.CharField(max_length=200)
	useful_votes = models.CharField(max_length=200)
	cool_votes = models.CharField(max_length=200)
	
class users(models.Model):
	user_id = models.CharField(max_length=200)
	review_count = models.IntegerField()
	stars = models.DecimalField(max_digits=5, decimal_places=4)

class tips(models.Model):
	business_id = models.CharField(max_length=200)
	user_id = models.CharField(max_length=200)
	likes = models.IntegerField()
	date = models.CharField(max_length=200)
	content = models.CharField(max_length=200)