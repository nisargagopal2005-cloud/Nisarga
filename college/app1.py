from datetime import date, datetime, time, timedelta
from decimal import Decimal

from flask import Flask, render_template, request, redirect, url_for, session
import mysql.connector
import os

app = Flask(__name__)
app.secret_key = "supersecretkey"
app.config['UPLOAD_FOLDER'] = 'static/uploads'

# Database Connection
def get_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # Add your MySQL password
        database="collegedb"
    )

# ---------------- HOME ----------------
@app.route('/')
def home():
    return render_template("home.html")

# ---------------- ADMIN LOGIN ----------------
@app.route('/admin', methods=['GET', 'POST'])
def admin():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']

        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM admintab WHERE username=%s AND password=%s",
                       (username, password))
        result = cursor.fetchone()
        conn.close()

        if result:
            session['admin'] = username
            return redirect(url_for('students'))
        else:
            return "Invalid Login"

    return render_template("admin.html")

# ---------------- LOGOUT ----------------
@app.route('/logout')
def logout():
    session.pop('admin', None)
    return redirect(url_for('home'))

# ---------------- STUDENT FORM ----------------
@app.route('/student', methods=['GET', 'POST'])
def student():
    if 'admin' not in session:
        return redirect(url_for('admin'))

    if request.method == 'POST':
        regno = request.form['regno']
        name = request.form['name']
        address = request.form['address']
        contact = request.form['contact']
        email = request.form['email']
        photo = request.files['photo']

        filename = photo.filename
        photo.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))

        conn = get_connection()
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO studenttab (regno, name, address, contact, email, photo)
            VALUES (%s, %s, %s, %s, %s, %s)
        """, (regno, name, address, contact, email, filename))
        conn.commit()
        conn.close()

        return redirect(url_for('students'))

    return render_template("student.html")

# ---------------- VIEW STUDENTS ----------------
@app.route('/students')
def students():
    if 'admin' not in session:
        return redirect(url_for('admin'))

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM studenttab")
    data = cursor.fetchall()
    conn.close()

    return render_template("view_students.html", students=data)

# ---------------- DELETE ----------------
@app.route('/delete/<regno>')
def delete(regno):
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM studenttab WHERE regno=%s", (regno,))
    conn.commit()
    conn.close()
    return redirect(url_for('students'))

# ---------------- EDIT ----------------
@app.route('/edit/<regno>', methods=['GET', 'POST'])
def edit(regno):
    conn = get_connection()
    cursor = conn.cursor()

    if request.method == 'POST':
        name = request.form['name']
        address = request.form['address']
        contact = request.form['contact']
        email = request.form['email']

        cursor.execute("""
            UPDATE studenttab
            SET name=%s, address=%s, contact=%s, email=%s
            WHERE regno=%s
        """, (name, address, contact, email, regno))

        conn.commit()
        conn.close()
        return redirect(url_for('students'))

    cursor.execute("SELECT * FROM studenttab WHERE regno=%s", (regno,))
    student: tuple[Decimal | bytes | date | datetime | float | int | set[str] | str | timedelta | None | time, ...] | dict[str, Decimal | bytes | date | datetime | float | int | set[str] | str | timedelta | None | time] | None = cursor.fetchone()
    conn.close()

    return render_template("edit.html", student=student)

if __name__ == "__main__":
    app.run(debug=True)