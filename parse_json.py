import json
import sqlite3


data_path = "data/"
filenames = ["yelp_academic_dataset_checkin.json", "yelp_academic_dataset_business.json", "yelp_academic_dataset_user.json", "yelp_academic_dataset_review.json", "yelp_academic_dataset_tip.json"]
results = {}

for file in filenames:
    with open(data_path + file, "r") as in_file:
        lines = in_file.readlines()
        jsons = [json.loads(x.strip()) for x in lines]
    results[file] = jsons

print len(results)

# http://docs.python.org/2/library/sqlite3.html
conn = sqlite3.connect("test.db")
c = conn.cursor()
c.execute("DROP TABLE IF EXISTS businesses")
c.execute("CREATE TABLE businesses (business_id text, name text, stars real, checkins text)")
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


c.execute("DROP TABLE IF EXISTS reviews")
# I think there are multiple kinds of votes that we may want to separate out
c.execute("CREATE TABLE reviews (business_id text, user_id text, stars real, "
          "content text, date text, funny_votes text, useful_votes text, cool_votes text)")
for r in results[filenames[3]]:
    c.execute("INSERT INTO reviews VALUES (?,?,?,?,?,?,?,?)", (r["business_id"], r["user_id"], r["stars"], r["text"],
                                                               r["date"], r["votes"]["funny"], r["votes"]["useful"],
                                                               r["votes"]["cool"]))

c.execute("DROP TABLE IF EXISTS users")
c.execute("CREATE TABLE users (user_id text, review_count integer, stars real)")
for u in results[filenames[2]]:
    c.execute("INSERT INTO users VALUES (?,?,?)", (u["user_id"], u["review_count"], u["average_stars"]))

# are we really going to use tips? current data doesn't have them anyway
c.execute("DROP TABLE IF EXISTS tips")
c.execute("CREATE TABLE tips (business_id text, user_id text, date text, likes integer, content text)")
for t in results[filenames[4]]:
    c.execute("INSERT INTO tips VALUES (?,?,?,?,?)", (t["business_id"], t["user_id"], t["date"], t["likes"], t["text"]))

for row in c.execute("SELECT * FROM businesses WHERE stars > 4.9"):
    print row
#for row in c.execute("SELECT * FROM users WHERE stars > 4.9"):
#    print row
#for row in c.execute("SELECT * FROM reviews WHERE stars > 4.9"):
#    print row