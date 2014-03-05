import json
import sqlite3
import datetime

idx = 0
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
c.execute("DROP TABLE IF EXISTS schemastuff_businesses")
c.execute("CREATE TABLE schemastuff_businesses (id integer, business_id text, name text, stars real, checkins text)")
for b in results[filenames[1]]:
    # businesses
    checkins = [x for x in results[filenames[0]] if x["business_id"] == b["business_id"]]

    if len(checkins) == 1:
        dict_as_str = str(checkins[0]["checkin_info"])
        c.execute("INSERT INTO schemastuff_businesses VALUES (?,?,?,?,?)", (idx, b["business_id"], b["name"], b["stars"], dict_as_str))
        idx += 1
    elif len(checkins) == 0:
        c.execute("INSERT INTO schemastuff_businesses VALUES (?,?,?,?,?)", (idx, b["business_id"], b["name"], b["stars"], ""))
        idx += 1
    else:
        assert False


c.execute("DROP TABLE IF EXISTS schemastuff_reviews")
c.execute("CREATE TABLE schemastuff_reviews (id integer, business_id text, user_id text, stars real, "
          "adj_stars real, content text, date text, day text, funny_votes text, useful_votes text, cool_votes text)")
for r in results[filenames[3]]:
    day_of_week = datetime.datetime.strptime(r["date"], '%Y-%m-%d').strftime('%A')
    avg_stars = [x["average_stars"] for x in results[filenames[2]] if x["user_id"] == r["user_id"]]
    adj_stars = float(r["stars"]) - float(avg_stars[0])
    c.execute("INSERT INTO schemastuff_reviews VALUES (?,?,?,?,?,?,?,?,?,?,?)", (idx, r["business_id"], r["user_id"],
                                                                            r["stars"], adj_stars, r["text"],
                                                                            r["date"], day_of_week, r["votes"]["funny"],
                                                                            r["votes"]["useful"], r["votes"]["cool"]))
    idx += 1

c.execute("DROP TABLE IF EXISTS schemastuff_users")
c.execute("CREATE TABLE schemastuff_users (id integer, user_id text, review_count integer, stars real)")
for u in results[filenames[2]]:
    c.execute("INSERT INTO schemastuff_users VALUES (?,?,?,?)", (idx, u["user_id"], u["review_count"], u["average_stars"]))
    idx += 1

c.execute("DROP TABLE IF EXISTS schemastuff_tips")
c.execute("CREATE TABLE schemastuff_tips (id integer, business_id text, user_id text, date text, likes integer, content text)")
for t in results[filenames[4]]:
    c.execute("INSERT INTO schemastuff_tips VALUES (?,?,?,?,?,?)", (idx, t["business_id"], t["user_id"], t["date"], t["likes"], t["text"]))
    idx += 1

#for row in c.execute("SELECT * FROM schemastuff_businesses WHERE stars > 4.9"):
    #print row
#for row in c.execute("SELECT * FROM users WHERE stars > 4.9"):
#    print row
for row in c.execute("SELECT * FROM reviews WHERE stars > 4.9"):
    print row