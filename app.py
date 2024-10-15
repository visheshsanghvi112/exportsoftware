import streamlit as st
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from datetime import datetime

# Initialize session state for storing student data, courses, and additional information
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
def record_attendance(student_index, course_name):
    today = datetime.today().date()
    if today not in st.session_state.attendance:
        st.session_state.attendance[today] = {}
    st.session_state.attendance[today][course_name] = student_index
    st.success(f"Attendance recorded for {st.session_state.students[student_index]['Name']} in {course_name}.")

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

# Function to save data to CSV
def save_data():
    df_students = pd.DataFrame(st.session_state.students)
    df_students.to_csv('students.csv', index=False)
    df_courses = pd.DataFrame(st.session_state.courses)
    df_courses.to_csv('courses.csv', index=False)
    df_projects = pd.DataFrame(st.session_state.projects)
    df_projects.to_csv('projects.csv', index=False)
    st.success("Data saved to students.csv, courses.csv, and projects.csv")

# Function to generate visualizations
def generate_visualizations():
    df = pd.DataFrame(st.session_state.students)
    if not df.empty:
        st.subheader("Visualizations")
        
        # Age Distribution
        st.write("### Age Distribution")
        sns.histplot(df['Age'], bins=10, kde=True)
        st.pyplot(plt)
        plt.clf()  # Clear the figure for the next plot

        # Grade Distribution
        st.write("### Grade Distribution")
        sns.countplot(x='Grade', data=df)
        st.pyplot(plt)
        plt.clf()

        # Gender Distribution
        st.write("### Gender Distribution")
        sns.countplot(x='Gender', data=df)
        st.pyplot(plt)
        plt.clf()

# Sidebar navigation
st.sidebar.title("Student Management System")
selection = st.sidebar.radio("Go to", ["Add Student", "View Students", "Manage Courses", "Attendance", "Grades", "Projects", "Statistics"])

# Add Student Page
if selection == "Add Student":
    st.title("Add Student")
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
    st.title("Students List")
    if st.session_state.students:
        df = pd.DataFrame(st.session_state.students)
        st.dataframe(df)

        # Search functionality
        search_name = st.text_input("Search by Name")
        if search_name:
            df = df[df['Name'].str.contains(search_name, case=False)]
            st.dataframe(df)
        else:
            st.dataframe(df)

        # Delete Student
        st.subheader("Delete Student")
        delete_student_index = st.selectbox("Select a student to delete", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
        delete_button = st.button("Delete Student")
        
        if delete_button:
            delete_student(delete_student_index)

        # Save Data Button
        if st.button("Save Data to CSV"):
            save_data()
    else:
        st.write("No students added yet.")

# Manage Courses Page
if selection == "Manage Courses":
    st.title("Manage Courses")
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
    st.title("Record Attendance")
    student_index = st.selectbox("Select Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    course_name = st.selectbox("Select Course", [course["Course Name"] for course in st.session_state.courses])
    
    record_attendance_button = st.button("Record Attendance")
    if record_attendance_button:
        record_attendance(student_index, course_name)

# Grades Page
if selection == "Grades":
    st.title("Add Grades")
    student_index = st.selectbox("Select Student for Grade", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    course_name = st.selectbox("Select Course", [course["Course Name"] for course in st.session_state.courses])
    grade = st.number_input("Grade", min_value=0, max_value=100, placeholder="Enter grade")
    
    add_grade_button = st.button("Add Grade")
    if add_grade_button:
        add_grade(student_index, course_name, grade)

# Projects Page
if selection == "Projects":
    st.title("Add Projects")
    student_index = st.selectbox("Select Student for Project", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
    project_name = st.text_input("Project Name", placeholder="Enter project name")
    deadline = st.date_input("Deadline", value=datetime.today())
    
    add_project_button = st.button("Add Project")
    if add_project_button:
        add_project(student_index, project_name, deadline)

# Statistics Page
if selection == "Statistics":
    st.title("Statistics and Visualizations")
    generate_visualizations()
