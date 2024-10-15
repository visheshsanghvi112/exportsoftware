import streamlit as st
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import plotly.express as px

# Initialize session state for storing student data, courses, and more
if 'students' not in st.session_state:
    st.session_state.students = []
if 'courses' not in st.session_state:
    st.session_state.courses = []
if 'attendance' not in st.session_state:
    st.session_state.attendance = {}
if 'grades' not in st.session_state:
    st.session_state.grades = {}
if 'projects' not in st.session_state:
    st.session_state.projects = []
if 'extracurriculars' not in st.session_state:
    st.session_state.extracurriculars = []
if 'notifications' not in st.session_state:
    st.session_state.notifications = []

# Header Section for the App
st.set_page_config(page_title="Student Management System", layout="wide", page_icon="🎓")
st.title("🎓 Student Management System")
st.markdown("""
Welcome to the **Student Management System**. 
This platform helps in managing students' data, tracking their attendance, grades, projects, and extracurricular activities.
""")

# Function to add a student
def add_student(name, age, email, grade, address, phone, enrollment_date, gender):
    student_data = {
        "Name": name,
        "Age": age,
        "Email": email,
        "Grade": grade,
        "Address": address,
        "Phone": phone,
        "Enrollment Date": enrollment_date,
        "Gender": gender
    }
    st.session_state.students.append(student_data)
    st.success(f"Student {name} added successfully!")

# Function to delete a student
def delete_student(index):
    st.session_state.students.pop(index)
    st.success("Student deleted successfully!")

# Function to add a course
def add_course(course_name, credits, instructor):
    course_data = {
        "Course Name": course_name,
        "Credits": credits,
        "Instructor": instructor
    }
    st.session_state.courses.append(course_data)
    st.success(f"Course {course_name} added successfully!")

# Function to record attendance
def record_attendance(student_index, course_name, status):
    today = datetime.today().date()
    if today not in st.session_state.attendance:
        st.session_state.attendance[today] = {}
    st.session_state.attendance[today][course_name] = {student_index: status}
    st.success(f"Attendance for {st.session_state.students[student_index]['Name']} marked as {status} in {course_name}.")

# Function to add grades
def add_grade(student_index, course_name, grade):
    if student_index not in st.session_state.grades:
        st.session_state.grades[student_index] = {}
    st.session_state.grades[student_index][course_name] = grade
    st.success(f"Grade {grade} added for {st.session_state.students[student_index]['Name']} in {course_name}.")

# Function to add a project
def add_project(student_index, project_name, deadline):
    project_data = {
        "Student": st.session_state.students[student_index]["Name"],
        "Project Name": project_name,
        "Deadline": deadline
    }
    st.session_state.projects.append(project_data)
    st.success(f"Project {project_name} added for {st.session_state.students[student_index]['Name']}!")

# Function to add extracurricular activity
def add_extracurricular(student_index, activity_name, role, date):
    activity_data = {
        "Student": st.session_state.students[student_index]["Name"],
        "Activity": activity_name,
        "Role": role,
        "Date": date
    }
    st.session_state.extracurriculars.append(activity_data)
    st.success(f"Activity {activity_name} added for {st.session_state.students[student_index]['Name']}!")

# Function to display notifications
def check_notifications():
    today = datetime.today().date()
    for project in st.session_state.projects:
        deadline = project["Deadline"]
        if (deadline - today).days <= 2:
            notification = f"⚠️ {project['Student']} has a project '{project['Project Name']}' due in {deadline - today} days."
            if notification not in st.session_state.notifications:
                st.session_state.notifications.append(notification)
    for notif in st.session_state.notifications:
        st.error(notif)

# Save Data to CSV
def save_data():
    df_students = pd.DataFrame(st.session_state.students)
    df_students.to_csv('students.csv', index=False)
    df_courses = pd.DataFrame(st.session_state.courses)
    df_courses.to_csv('courses.csv', index=False)
    df_projects = pd.DataFrame(st.session_state.projects)
    df_projects.to_csv('projects.csv', index=False)
    df_extracurriculars = pd.DataFrame(st.session_state.extracurriculars)
    df_extracurriculars.to_csv('extracurriculars.csv', index=False)
    st.success("Data saved successfully to CSV files.")

# Sidebar Navigation
st.sidebar.title("Navigation")
selection = st.sidebar.radio("Go to", [
    "Dashboard", "Add Student", "View Students", "Manage Courses", 
    "Attendance", "Grades", "Projects", "Extracurriculars"
])

# Dashboard
if selection == "Dashboard":
    st.header("📊 Dashboard Overview")
    st.write("View the overall statistics of students, courses, attendance, and more.")
    st.write(f"Total Students: {len(st.session_state.students)}")
    st.write(f"Total Courses: {len(st.session_state.courses)}")
    st.write(f"Total Projects: {len(st.session_state.projects)}")
    st.write(f"Total Extracurriculars: {len(st.session_state.extracurriculars)}")

    # Check Notifications for Upcoming Deadlines
    check_notifications()

# Add Student Page
if selection == "Add Student":
    st.header("Add a New Student")
    with st.form(key='add_student_form'):
        name = st.text_input("Student Name", placeholder="Enter full name")
        age = st.number_input("Age", min_value=1, max_value=100, placeholder="Age")
        email = st.text_input("Email", placeholder="example@example.com")
        grade = st.selectbox("Grade", options=["A", "B", "C", "D", "F"])
        address = st.text_input("Address", placeholder="Enter address")
        phone = st.text_input("Phone", placeholder="Enter phone number")
        enrollment_date = st.date_input("Enrollment Date", value=datetime.today())
        gender = st.selectbox("Gender", options=["Male", "Female", "Other"])
        
        submit_button = st.form_submit_button("Add Student")
        if submit_button:
            if name and email:
                add_student(name, age, email, grade, address, phone, enrollment_date, gender)
            else:
                st.warning("Please fill all fields!")

# View Students Page
if selection == "View Students":
    st.header("Students List")
    if st.session_state.students:
        df_students = pd.DataFrame(st.session_state.students)
        st.dataframe(df_students)

        # Delete Student
        delete_student_index = st.selectbox("Select a student to delete", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
        if st.button("Delete Student"):
            delete_student(delete_student_index)

        # Save Data Button
        if st.button("Save Data to CSV"):
            save_data()
    else:
        st.write("No students added yet.")

# Manage Courses Page
if selection == "Manage Courses":
    st.header("Manage Courses")
    with st.form(key='add_course_form'):
        course_name = st.text_input("Course Name", placeholder="Enter course name")
        credits = st.number_input("Credits", min_value=1, max_value=10, placeholder="Enter course credits")
        instructor = st.text_input("Instructor", placeholder="Enter instructor name")
        add_course_button = st.form_submit_button("Add Course")
        if add_course_button:
            add_course(course_name, credits, instructor)

    st.subheader("Available Courses")
    course_df = pd.DataFrame(st.session_state.courses)
    st.dataframe(course_df)

# Attendance Page
if selection == "Attendance":
    st.header("Record Attendance")
    student_index = st.selectbox("Select Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    course_name = st.selectbox("Select Course", [course["Course Name"] for course in st.session_state.courses])
    status = st.selectbox("Attendance Status", options=["Present", "Absent", "Late"])
    if st.button("Mark Attendance"):
        record_attendance(student_index, course_name, status)

# Grades Page
if selection == "Grades":
    st.header("Record Grades")
    student_index = st.selectbox("Select Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    course_name = st.selectbox("Select Course", [course["Course Name"] for course in st.session_state.courses])
    grade = st.selectbox("Grade", options=["A", "B", "C", "D", "F"])
    if st.button("Add Grade"):
        add_grade(student_index, course_name, grade)

# Projects Page
if selection == "Projects":
    st.header("Manage Student Projects")
    student_index = st.selectbox("Select Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    project_name = st.text_input("Project Name", placeholder="Enter project name")
    deadline = st.date_input("Deadline", value=datetime.today() + timedelta(days=7))
    if st.button("Add Project"):
        add_project(student_index, project_name, deadline)

    st.subheader("Student Projects")
    projects_df = pd.DataFrame(st.session_state.projects)
    st.dataframe(projects_df)

# Extracurriculars Page
if selection == "Extracurriculars":
    st.header("Manage Extracurricular Activities")
    student_index = st.selectbox("Select Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    activity_name = st.text_input("Activity Name", placeholder="Enter activity name")
    role = st.text_input("Role", placeholder="Enter student's role in activity")
    activity_date = st.date_input("Date of Activity", value=datetime.today())
    if st.button("Add Activity"):
        add_extracurricular(student_index, activity_name, role, activity_date)

    st.subheader("Extracurricular Activities")
    extracurriculars_df = pd.DataFrame(st.session_state.extracurriculars)
    st.dataframe(extracurriculars_df)
