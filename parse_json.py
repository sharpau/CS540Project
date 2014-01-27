import json

data_path = "data/yelp_phoenix_academic_dataset/"
filenames = ["yelp_academic_dataset_checkin.json", "yelp_academic_dataset_business.json", "yelp_academic_dataset_user.json", "yelp_academic_dataset_review.json"]
results = {}

for file in filenames:
    with open(data_path + file, "r") as in_file:
        lines = in_file.readlines()
        jsons = [json.loads(x.strip()) for x in lines]
    results[file] = jsons

print len(results)
