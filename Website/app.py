from flask import Flask, render_template, request, redirect, url_for, session
import mysql.connector

app = Flask(__name__)
app.secret_key = "secretkey"

# MySQL Connection
def get_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # put your MySQL password if any
        database="flaskproject"
    )

# Home
@app.route('/')
def home():
    return render_template("home.html")

# Admin Login
@app.route('/admin_login', methods=['GET','POST'])
def admin_login():
    if request.method == "POST":
        loginname = request.form['login name']
        password = request.form['password']

        conn = get_connection()
        cur = conn.cursor()
        cur.execute("SELECT * FROM admintab WHERE loginname=%s AND password=%s",
                    (loginname, password))
        data = cur.fetchone()
        conn.close()

        if data:
            session['admin'] = loginname
            return redirect(url_for('admin_dashboard'))

    return render_template("admin_login.html")

# Admin Dashboard
@app.route('/admin_dashboard')
def admin_dashboard():
    if 'admin' not in session:
        return redirect(url_for('admin_login'))

    conn = get_connection()
    cur = conn.cursor()
    cur.execute("SELECT * FROM usertab")
    users = cur.fetchall()
    conn.close()

    return render_template("admin_dashboard.html", users=users)

# Approve User
@app.route('/approve/<int:id>')
def approve(id):
    conn = get_connection()
    cur = conn.cursor()
    cur.execute("UPDATE usertab SET status='approved' WHERE id=%s", (id,))
    conn.commit()
    conn.close()
    return redirect(url_for('admin_dashboard'))

# User Registration
@app.route('/user_register', methods=['GET','POST'])
def user_register():
    if request.method == "POST":
        username = request.form['username']
        password = request.form['password']

        conn = get_connection()
        cur = conn.cursor()
        cur.execute("INSERT INTO usertab (username,password,status) VALUES (%s,%s,%s)",
                    (username, password, 'pending'))
        conn.commit()
        conn.close()

        return redirect(url_for('user_login'))

    return render_template("user_register.html")

# User Login
@app.route('/user_login', methods=['GET','POST'])
def user_login():
    if request.method == "POST":
        username = request.form['username']
        password = request.form['password']

        conn = get_connection()
        cur = conn.cursor()
        cur.execute("""SELECT * FROM usertab 
                       WHERE username=%s AND password=%s AND status='approved'""",
                    (username, password))
        data = cur.fetchone()
        conn.close()

        if data:
            session['user'] = username
            return redirect(url_for('user_dashboard'))

    return render_template("user_login.html")

# User Dashboard
@app.route('/user_dashboard')
def user_dashboard():
    if 'user' not in session:
        return redirect(url_for('user_login'))
    return render_template("user_dashboard.html")

# Logout
@app.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('home'))

# About
@app.route('/about')
def about():
    return render_template("about.html")

# Contact
@app.route('/contact')
def contact():
    return render_template("contact.html")

if __name__ == "__main__":
    app.run(debug=True)