import tkinter as tk
from tkinter import messagebox, ttk
from pymongo import MongoClient

# Connect to MongoDB
client = MongoClient("mongodb://localhost:27017/")
db = client["pyproject"]
students_collection = db["students"]
courses_collection = db["courses"]
grades_collection = db["grades"]
attendance_collection = db["attendance"]

# Main Application Class
class StudentManagementApp:
    def __init__(self, root):
        self.root = root
        self.root.title("PG Student Management System")
        self.root.geometry("700x600")
        self.root.config(bg="#34495E")
        
        self.create_widgets()
    
    def create_widgets(self):
        # Create Tabs
        self.tab_control = ttk.Notebook(self.root)
        
        self.student_tab = ttk.Frame(self.tab_control)
        self.course_tab = ttk.Frame(self.tab_control)
        self.grade_tab = ttk.Frame(self.tab_control)
        self.attendance_tab = ttk.Frame(self.tab_control)

        self.tab_control.add(self.student_tab, text="Students")
        self.tab_control.add(self.course_tab, text="Courses")
        self.tab_control.add(self.grade_tab, text="Grades")
        self.tab_control.add(self.attendance_tab, text="Attendance")
        self.tab_control.pack(expand=1, fill="both")

        self.create_student_tab()
        self.create_course_tab()
        self.create_grade_tab()
        self.create_attendance_tab()

    def create_student_tab(self):
        # Student Management Widgets
        tk.Label(self.student_tab, text="Student Name", bg="#34495E", fg="white").grid(row=0, column=0, padx=5, pady=5, sticky='e')
        self.student_name_entry = tk.Entry(self.student_tab, bg="#2C3E50", fg="white")
        self.student_name_entry.grid(row=0, column=1, padx=5, pady=5)

        tk.Label(self.student_tab, text="Student ID", bg="#34495E", fg="white").grid(row=1, column=0, padx=5, pady=5, sticky='e')
        self.student_id_entry = tk.Entry(self.student_tab, bg="#2C3E50", fg="white")
        self.student_id_entry.grid(row=1, column=1, padx=5, pady=5)

        tk.Label(self.student_tab, text="Email", bg="#34495E", fg="white").grid(row=2, column=0, padx=5, pady=5, sticky='e')
        self.student_email_entry = tk.Entry(self.student_tab, bg="#2C3E50", fg="white")
        self.student_email_entry.grid(row=2, column=1, padx=5, pady=5)

        tk.Button(self.student_tab, text="Add Student", command=self.add_student, bg="#27AE60", fg="black").grid(row=3, column=0, padx=5)
        tk.Button(self.student_tab, text="View Students", command=self.view_students, bg="#2980B9", fg="black").grid(row=3, column=1, padx=5)

        self.student_tree = ttk.Treeview(self.student_tab, columns=("Name", "ID", "Email"), show="headings")
        self.student_tree.heading("Name", text="Student Name")
        self.student_tree.heading("ID", text="Student ID")
        self.student_tree.heading("Email", text="Email")
        self.student_tree.grid(row=4, column=0, columnspan=2, padx=10, pady=10)

    def create_course_tab(self):
        # Course Management Widgets
        tk.Label(self.course_tab, text="Course Name", bg="#34495E", fg="white").grid(row=0, column=0, padx=5, pady=5, sticky='e')
        self.course_name_entry = tk.Entry(self.course_tab, bg="#2C3E50", fg="white")
        self.course_name_entry.grid(row=0, column=1, padx=5, pady=5)

        tk.Label(self.course_tab, text="Course Code", bg="#34495E", fg="white").grid(row=1, column=0, padx=5, pady=5, sticky='e')
        self.course_code_entry = tk.Entry(self.course_tab, bg="#2C3E50", fg="white")
        self.course_code_entry.grid(row=1, column=1, padx=5, pady=5)

        tk.Label(self.course_tab, text="Credits", bg="#34495E", fg="white").grid(row=2, column=0, padx=5, pady=5, sticky='e')
        self.course_credits_entry = tk.Entry(self.course_tab, bg="#2C3E50", fg="white")
        self.course_credits_entry.grid(row=2, column=1, padx=5, pady=5)

        tk.Button(self.course_tab, text="Add Course", command=self.add_course, bg="#27AE60", fg="black").grid(row=3, column=0, padx=5)
        tk.Button(self.course_tab, text="View Courses", command=self.view_courses, bg="#2980B9", fg="black").grid(row=3, column=1, padx=5)

        self.course_tree = ttk.Treeview(self.course_tab, columns=("Name", "Code", "Credits"), show="headings")
        self.course_tree.heading("Name", text="Course Name")
        self.course_tree.heading("Code", text="Course Code")
        self.course_tree.heading("Credits", text="Credits")
        self.course_tree.grid(row=4, column=0, columnspan=2, padx=10, pady=10)

    def create_grade_tab(self):
        # Grade Management Widgets
        tk.Label(self.grade_tab, text="Student ID", bg="#34495E", fg="white").grid(row=0, column=0, padx=5, pady=5, sticky='e')
        self.grade_student_id_entry = tk.Entry(self.grade_tab, bg="#2C3E50", fg="white")
        self.grade_student_id_entry.grid(row=0, column=1, padx=5, pady=5)

        tk.Label(self.grade_tab, text="Grade", bg="#34495E", fg="white").grid(row=1, column=0, padx=5, pady=5, sticky='e')
        self.grade_entry = tk.Entry(self.grade_tab, bg="#2C3E50", fg="white")
        self.grade_entry.grid(row=1, column=1, padx=5, pady=5)

        tk.Label(self.grade_tab, text="Semester", bg="#34495E", fg="white").grid(row=2, column=0, padx=5, pady=5, sticky='e')
        self.semester_entry = tk.Entry(self.grade_tab, bg="#2C3E50", fg="white")
        self.semester_entry.grid(row=2, column=1, padx=5, pady=5)

        tk.Button(self.grade_tab, text="Add Grade", command=self.add_grade, bg="#27AE60", fg="black").grid(row=3, column=0, padx=5)
        tk.Button(self.grade_tab, text="View Grades", command=self.view_grades, bg="#2980B9", fg="black").grid(row=3, column=1, padx=5)

        self.grade_tree = ttk.Treeview(self.grade_tab, columns=("Student ID", "Grade", "Semester"), show="headings")
        self.grade_tree.heading("Student ID", text="Student ID")
        self.grade_tree.heading("Grade", text="Grade")
        self.grade_tree.heading("Semester", text="Semester")
        self.grade_tree.grid(row=4, column=0, columnspan=2, padx=10, pady=10)

    def create_attendance_tab(self):
        # Attendance Management Widgets
        tk.Label(self.attendance_tab, text="Student ID", bg="#34495E", fg="white").grid(row=0, column=0, padx=5, pady=5, sticky='e')
        self.attendance_student_id_entry = tk.Entry(self.attendance_tab, bg="#2C3E50", fg="white")
        self.attendance_student_id_entry.grid(row=0, column=1, padx=5, pady=5)

        tk.Label(self.attendance_tab, text="Attendance Date", bg="#34495E", fg="white").grid(row=1, column=0, padx=5, pady=5, sticky='e')
        self.attendance_date_entry = tk.Entry(self.attendance_tab, bg="#2C3E50", fg="white")
        self.attendance_date_entry.grid(row=1, column=1, padx=5, pady=5)

        tk.Label(self.attendance_tab, text="Status", bg="#34495E", fg="white").grid(row=2, column=0, padx=5, pady=5, sticky='e')
        self.attendance_status_entry = tk.Entry(self.attendance_tab, bg="#2C3E50", fg="white")
        self.attendance_status_entry.grid(row=2, column=1, padx=5, pady=5)

        tk.Button(self.attendance_tab, text="Add Attendance", command=self.add_attendance, bg="#27AE60", fg="black").grid(row=3, column=0, padx=5)
        tk.Button(self.attendance_tab, text="View Attendance", command=self.view_attendance, bg="#2980B9", fg="black").grid(row=3, column=1, padx=5)

        self.attendance_tree = ttk.Treeview(self.attendance_tab, columns=("Student ID", "Date", "Status"), show="headings")
        self.attendance_tree.heading("Student ID", text="Student ID")
        self.attendance_tree.heading("Date", text="Date")
        self.attendance_tree.heading("Status", text="Status")
        self.attendance_tree.grid(row=4, column=0, columnspan=2, padx=10, pady=10)

    def add_student(self):
        student_name = self.student_name_entry.get()
        student_id = self.student_id_entry.get()
        student_email = self.student_email_entry.get()

        if student_name and student_id and student_email:
            student_data = {
                "name": student_name,
                "student_id": student_id,
                "email": student_email
            }
            students_collection.insert_one(student_data)
            messagebox.showinfo("Success", "Student added successfully!")
            self.clear_student_entries()
            self.view_students()
        else:
            messagebox.showwarning("Input Error", "Please fill in all fields.")

    def view_students(self):
        for row in self.student_tree.get_children():
            self.student_tree.delete(row)
        for student in students_collection.find():
            self.student_tree.insert("", "end", values=(student["name"], student["student_id"], student["email"]))

    def add_course(self):
        course_name = self.course_name_entry.get()
        course_code = self.course_code_entry.get()
        course_credits = self.course_credits_entry.get()

        if course_name and course_code and course_credits:
            course_data = {
                "name": course_name,
                "code": course_code,
                "credits": course_credits
            }
            courses_collection.insert_one(course_data)
            messagebox.showinfo("Success", "Course added successfully!")
            self.clear_course_entries()
            self.view_courses()
        else:
            messagebox.showwarning("Input Error", "Please fill in all fields.")

    def view_courses(self):
        for row in self.course_tree.get_children():
            self.course_tree.delete(row)
        for course in courses_collection.find():
            self.course_tree.insert("", "end", values=(course["name"], course["code"], course["credits"]))

    def add_grade(self):
        student_id = self.grade_student_id_entry.get()
        grade = self.grade_entry.get()
        semester = self.semester_entry.get()

        if student_id and grade and semester:
            grade_data = {
                "student_id": student_id,
                "grade": grade,
                "semester": semester
            }
            grades_collection.insert_one(grade_data)
            messagebox.showinfo("Success", "Grade added successfully!")
            self.clear_grade_entries()
            self.view_grades()
        else:
            messagebox.showwarning("Input Error", "Please fill in all fields.")

    def view_grades(self):
        for row in self.grade_tree.get_children():
            self.grade_tree.delete(row)
        for grade in grades_collection.find():
            self.grade_tree.insert("", "end", values=(grade["student_id"], grade["grade"], grade["semester"]))

    def add_attendance(self):
        student_id = self.attendance_student_id_entry.get()
        attendance_date = self.attendance_date_entry.get()
        status = self.attendance_status_entry.get()

        if student_id and attendance_date and status:
            attendance_data = {
                "student_id": student_id,
                "date": attendance_date,
                "status": status
            }
            attendance_collection.insert_one(attendance_data)
            messagebox.showinfo("Success", "Attendance added successfully!")
            self.clear_attendance_entries()
            self.view_attendance()
        else:
            messagebox.showwarning("Input Error", "Please fill in all fields.")

    def view_attendance(self):
        for row in self.attendance_tree.get_children():
            self.attendance_tree.delete(row)
        for attendance in attendance_collection.find():
            self.attendance_tree.insert("", "end", values=(attendance["student_id"], attendance["date"], attendance["status"]))

    def clear_student_entries(self):
        self.student_name_entry.delete(0, tk.END)
        self.student_id_entry.delete(0, tk.END)
        self.student_email_entry.delete(0, tk.END)

    def clear_course_entries(self):
        self.course_name_entry.delete(0, tk.END)
        self.course_code_entry.delete(0, tk.END)
        self.course_credits_entry.delete(0, tk.END)

    def clear_grade_entries(self):
        self.grade_student_id_entry.delete(0, tk.END)
        self.grade_entry.delete(0, tk.END)
        self.semester_entry.delete(0, tk.END)

    def clear_attendance_entries(self):
        self.attendance_student_id_entry.delete(0, tk.END)
        self.attendance_date_entry.delete(0, tk.END)
        self.attendance_status_entry.delete(0, tk.END)

# Run the Application
if __name__ == "__main__":
    root = tk.Tk()
    app = StudentManagementApp(root)
    root.mainloop()
