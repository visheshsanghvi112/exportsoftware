import streamlit as st
from pymongo import MongoClient

# MongoDB connection details
MONGO_URI = "mongodb://localhost:27017/"  # Update with your MongoDB URI if needed
DATABASE_NAME = "vishesh"  # Your database name
COLLECTION_NAME = "students"  # Replace with your collection name

# Connect to MongoDB
client = MongoClient(MONGO_URI)
db = client[DATABASE_NAME]
collection = db[COLLECTION_NAME]

# Streamlit app title
st.title("Student Management System")

# Function to display data
def display_data():
    st.subheader("Data from MongoDB")
    data = list(collection.find())
    if data:
        for item in data:
            st.write(item)
    else:
        st.write("No data found.")

# Function to add a new student record
def add_student():
    st.subheader("Add a New Student Record")
    name = st.text_input("Name")
    age = st.number_input("Age", min_value=0)
    email = st.text_input("Email")
    course = st.text_input("Course")

    if st.button("Add Student"):
        if name and email and course:
            collection.insert_one({"name": name, "age": age, "email": email, "course": course})
            st.success("Student record added successfully!")
        else:
            st.error("Please fill in all fields.")

# Navigation sidebar
st.sidebar.title("Navigation")
option = st.sidebar.selectbox("Choose an action", ["View Data", "Add Student"])

if option == "View Data":
    display_data()
elif option == "Add Student":
    add_student()

# Close MongoDB connection
client.close()
