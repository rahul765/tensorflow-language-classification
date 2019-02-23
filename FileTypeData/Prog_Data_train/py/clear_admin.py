import sqlite3

db=sqlite3.connect('admin.db')
db.execute('delete from [admin]')
db.commit()
db.close()
