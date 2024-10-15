import streamlit as st
import pandas as pd
import numpy as np
from datetime import datetime

# Initialize session state for storing student data and courses
if 'students' not in st.session_state:
    st.session_state.students = []
if 'courses' not in st.session_state:
    st.session_state.courses = []
if 'attendance' not in st.session_state:
    st.session_state.attendance = {}
if 'grades' not in st.session_state:
    st.session_state.grades = {}

# Function to add a student
def add_student(name, age, email, grade):
    student_data = {
        "Name": name,
        "Age": age,
        "Email": email,
        "Grade": grade
    }
    st.session_state.students.append(student_data)
    st.success(f"Student {name} added successfully!")

# Function to delete a student
def delete_student(index):
    st.session_state.students.pop(index)
    st.success("Student deleted successfully!")

# Function to add a course
def add_course(course_name):
    st.session_state.courses.append(course_name)
    st.success(f"Course {course_name} added successfully!")

# Function to record attendance
def record_attendance(student_index):
    today = datetime.today().date()
    if today not in st.session_state.attendance:
        st.session_state.attendance[today] = {}
    st.session_state.attendance[today][student_index] = True
    st.success(f"Attendance recorded for {st.session_state.students[student_index]['Name']}.")

# Function to add grades
def add_grade(student_index, course_name, grade):
    if student_index not in st.session_state.grades:
        st.session_state.grades[student_index] = {}
    st.session_state.grades[student_index][course_name] = grade
    st.success(f"Grade {grade} added for {st.session_state.students[student_index]['Name']} in {course_name}.")

# Function to save data to CSV
def save_data():
    df_students = pd.DataFrame(st.session_state.students)
    df_students.to_csv('students.csv', index=False)
    df_courses = pd.DataFrame(st.session_state.courses, columns=["Courses"])
    df_courses.to_csv('courses.csv', index=False)
    st.success("Data saved to students.csv and courses.csv")

# Sidebar navigation
st.sidebar.title("Student Management System")
selection = st.sidebar.radio("Go to", ["Add Student", "View Students", "Manage Courses", "Attendance", "Grades", "Statistics"])

# Add Student Page
if selection == "Add Student":
    st.title("Add Student")
    with st.form(key='add_student_form'):
        name = st.text_input("Student Name", placeholder="Enter full name")
        age = st.number_input("Age", min_value=1, max_value=100, placeholder="Age")
        email = st.text_input("Email", placeholder="example@example.com")
        grade = st.selectbox("Grade", options=["A", "B", "C", "D", "F"])
        
        submit_button = st.form_submit_button("Add Student")
        if submit_button:
            if name and email:
                add_student(name, age, email, grade)
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
        add_course_button = st.form_submit_button("Add Course")
        if add_course_button:
            add_course(course_name)

    st.subheader("Available Courses")
    st.write(st.session_state.courses)

# Attendance Page
if selection == "Attendance":
    st.title("Record Attendance")
    if st.session_state.students:
        st.subheader("Today's Date: " + str(datetime.today().date()))
        for i, student in enumerate(st.session_state.students):
            if st.checkbox(f"Present: {student['Name']}", key=i):
                record_attendance(i)

# Grades Page
if selection == "Grades":
    st.title("Add Grades")
    if st.session_state.students:
        student_index = st.selectbox("Select a Student", range(len(st.session_state.students)), format_func=lambda x: st.session_state.students[x]["Name"])
        course_name = st.selectbox("Select Course", st.session_state.courses)
        grade = st.text_input("Enter Grade", placeholder="A, B, C, etc.")

        if st.button("Add Grade"):
            if grade:
                add_grade(student_index, course_name, grade)
            else:
                st.warning("Please enter a grade.")

        st.subheader("Grades Overview")
        grades_df = pd.DataFrame(st.session_state.grades).T
        st.dataframe(grades_df)

# Statistics Page
if selection == "Statistics":
    st.title("Statistics")
    if st.session_state.students:
        df = pd.DataFrame(st.session_state.students)

        # Display age distribution
        st.subheader("Age Distribution")
        st.bar_chart(df['Age'].value_counts())

        # Display grade distribution
        st.subheader("Grade Distribution")
        st.bar_chart(df['Grade'].value_counts())

        # Display total number of students
        st.write(f"Total number of students: {len(df)}")
    else:
        st.write("No student data available for statistics.")
