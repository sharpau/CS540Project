import json

data_path = "data/yelp_phoenix_academic_dataset/"
checkin_file = "yelp_academic_dataset_checkin.json"

with open(data_path + checkin_file, "r") as in_file:
    lines = in_file.readlines()
    jsons = [json.loads(x.strip()) for x in lines]
    print len(jsons)

for j in jsons:
    # insert into a table
    print j