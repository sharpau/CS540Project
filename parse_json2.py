import json
import sqlite3
import datetime


data_path = "data/"
filenames = ["yelp_academic_dataset_checkin.json", "yelp_academic_dataset_business.json", "yelp_academic_dataset_user.json", "yelp_academic_dataset_review.json", "yelp_academic_dataset_tip.json"]
results = {}

for file in filenames:
    with open(data_path + file, "r") as in_file:
        lines = in_file.readlines()
        jsons = [json.loads(x.strip()) for x in lines]
    results[file] = jsons

print "Parsing jsons into dicts done"

# http://docs.python.org/2/library/sqlite3.html
conn = sqlite3.connect("test.db")
c = conn.cursor()
c.execute("DROP TABLE IF EXISTS businesses")
c.execute("CREATE TABLE businesses ( business_id text, name text, stars real, checkins text)")
c.execute("CREATE INDEX business_id_idx ON businesses (business_id)")
for b in results[filenames[1]]:
    # businesses
    checkins = [x for x in results[filenames[0]] if x["business_id"] == b["business_id"]]

    if len(checkins) == 1:
        dict_as_str = str(checkins[0]["checkin_info"])
        c.execute("INSERT INTO businesses VALUES (?,?,?,?)", (b["business_id"], b["name"], b["stars"], dict_as_str))

    elif len(checkins) == 0:
        c.execute("INSERT INTO businesses VALUES (?,?,?,?)", (b["business_id"], b["name"], b["stars"], ""))
        
    else:
        assert False

print "Businesses done"

user_avg_dict = {}
user_fans_dict = {}
user_avg_votes_dict = {}
c.execute("DROP TABLE IF EXISTS users")
c.execute("CREATE TABLE users (user_id text, review_count integer, stars real)")
for u in results[filenames[2]]:
    c.execute("INSERT INTO users VALUES (?,?,?)", (u["user_id"], u["review_count"], u["average_stars"]))
    user_avg_dict[u["user_id"]] = float(u["average_stars"])
    user_fans_dict[u["user_id"]] = float(u["fans"])
    user_avg_votes_dict[u["user_id"]] = (float(u["votes"]["funny"]) + float(u["votes"]["cool"]) + float(u["votes"]["useful"]) + 3) / float(u["review_count"])

print "Users done"

c.execute("DROP TABLE IF EXISTS reviews")
c.execute("CREATE TABLE reviews (business_id text, user_id text, stars real, "
          "adj_stars real, content text, date text, day text, funny_votes integer, useful_votes integer, cool_votes integer, "
          "user_fans int, user_avg_votes real, breakfast int, lunch int, dinner int)")
c.execute("CREATE INDEX review_business_id_idx ON reviews(business_id)")

for r in results[filenames[3]]:
    breakfast = 0
    lunch = 0
    dinner = 0
    if r["text"].find("breakfast") != -1 or r["text"].find("morning") != -1:
        breakfast = 1
    if r["text"].find("lunch") != -1 or r["text"].find("afternoon") != -1 or r["text"].find("midday") != -1:
        lunch = 1
    if r["text"].find("dinner") != -1 or r["text"].find("evening") != -1 or r["text"].find("night") != -1 or r["text"].find("drinks") != -1:
        dinner = 1


    user_id = r["user_id"]
    day_of_week = datetime.datetime.strptime(r["date"], '%Y-%m-%d').strftime('%A')
    adj_stars = float(r["stars"]) - user_avg_dict[user_id]
    c.execute("INSERT INTO reviews VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", (r["business_id"], user_id,
                                                                            r["stars"], adj_stars, r["text"],
                                                                            r["date"], day_of_week, r["votes"]["funny"],
                                                                            r["votes"]["useful"], r["votes"]["cool"],
                                                                            user_fans_dict[user_id], user_avg_votes_dict[user_id],
                                                                            breakfast, lunch, dinner))


print "Reviews done"

c.execute("DROP TABLE IF EXISTS tips")
c.execute("CREATE TABLE tips (business_id text, user_id text, date text, likes integer, content text)")
for t in results[filenames[4]]:
    c.execute("INSERT INTO tips VALUES (?,?,?,?,?)", (t["business_id"], t["user_id"], t["date"], t["likes"], t["text"]))


print "Tips done"

#for row in c.execute("SELECT * FROM businesses WHERE stars > 4.9"):
    #print row
#for row in c.execute("SELECT * FROM users WHERE stars > 4.9"):
#    print row
#for row in c.execute("SELECT * FROM reviews WHERE stars > 4.9 AND dinner = 1"):
#    print row