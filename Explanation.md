# **User Management System Documentation in WordPress**

## **Problem to Solve**

Create a user management system with custom roles in WordPress. Users can register or log in. Depending on their assigned role (**Cool Kid, Cooler Kid, Coolest Kid**), they will have access to different levels of information within the system:

- **Cool Kid**: Can only view their own information.
- **Cooler Kid**: Can view limited information about other users (**name and country**).
- **Coolest Kid**: Can view complete information about other users (**name, country, email and role**).

Additionally, an administrator will have the ability to modify user roles through a **protected API endpoint**.

---

## **Technical Design Specification**

The system has been implemented as a **custom theme in WordPress**, ensuring flexibility and maintainability. Below are the key technical aspects:

### **1. Custom Theme Structure**

A custom theme has been developed to define the necessary structure for managing different types of users and their permissions. The theme includes the following main features:

#### **User Registration and Authentication**

- A **registration form** has been created where users enter their email.
- Since this is an educational project, all users are assigned the default password **"1q2w3e"**.
- A **login form** has been implemented based on email and password authentication.

#### **Custom Roles**

The following **custom user roles** have been created in WordPress:

- **Cool Kid**: Standard user, can only view their own information.
- **Cooler Kid**: Can view the **name and country** of other users.
- **Coolest Kid**: Can view **name, country, email, and role** of other users.

These roles determine permissions and access to information within the system.

#### **Automatic User Data Generation**

- When a user registers, their personal information is automatically generated using the **public API randomuser.me**.
- The generated data includes: **first name, last name, country, and email**.

---

### **2. Implemented Pages and Features**

#### **Profile Page (`/profile`)**

- Displays information about the authenticated user.
- If the user has permissions, it also displays information about other users according to their role.

#### **API for Role Changes**

- A **protected endpoint** (`/wp-json/cool-kids/v1/change-role`) has been developed that allows changing a user’s role using their **email** or a combination of **first and last name**.
- This endpoint only accepts valid roles: **Cool Kid, Cooler Kid, Coolest Kid**.
- **JWT authentication** has been implemented to protect requests.

---

### **3. Security and Best Practices**

- **JWT authentication** has been integrated.
- **Permissions are restricted** based on the user’s role.
- **Form validations** have been implemented to prevent duplicate registrations or incorrect data also error and success messages.

---

## **Technical Decisions and Justifications**

Several technical decisions were made to ensure an **efficient, secure, and scalable system**. The most important ones are detailed below:

### **1. Custom Theme Development**

- A **custom WordPress theme** was developed instead of modifying an existing theme.
- This allows for **greater flexibility and maintenance**, ensuring that the code fully adapts to the system's requirements.

### **2. Use of WordPress Standards**

- **Best practices and WordPress standards** were followed.
- Native functions like `wp_remote_get()` were used for HTTP requests.
- The **WordPress hooks and filters structure** was respected to ensure modularity.

### **3. Custom Role Creation**

- Custom roles (**Cool Kid, Cooler Kid, Coolest Kid**) were implemented for better control over system permissions.

### **4. Use of JavaScript for Improved Frontend Experience**

- **JavaScript** features were incorporated to enhance user interaction on the site.

### **5. Implementation of SASS for Style Management**

- **SASS** was used to improve CSS code organization.

### **6. Implementation of JWT for Secure Authentication**

- **JSON Web Tokens (JWT)** authentication was implemented to protect sensitive requests.

### **7. Use of WordPress Coding Standards (Linter)**

- **WordPress Coding Standards** were implemented via a **Linter** to maintain code quality.

---

## **How Our Solution Meets the Administrator's Requirements**

### **1. User Registration and Management**

- A **clear and accessible registration flow** was developed.
- Each user’s identity is automatically generated using the **randomuser.me API**.

### **2. Role-Based Access Control**

- Three user levels (**Cool Kid, Cooler Kid, Coolest Kid**) have been defined, each with specific permissions.

### **3. Administrator Functionality: Role Changes via API**

- A **protected API endpoint** allows administrators to modify user roles.
- The administrator can update roles by sending an **HTTP request with JWT authentication**.

### **4. Security and Best Practices**

- **JWT authentication** secures API requests.
- **Backend validations** ensure only allowed roles are assigned.

---

# **How to Change User Roles via API in WordPress**

## **1. Obtaining the JWT Authentication Token**

### **Endpoint to Obtain the Token**
```
POST /wp-json/jwt-auth/v1/token
```

### **Example of a Valid Request**
```sh
curl -X POST https://yourwebsite.com/wp-json/jwt-auth/v1/token
     -H "Content-Type: application/json"
     -d '{
           "username": "admin",
           "password": "your_password"
         }'
```
### **Success (200 OK)**
```json
{
    "token": "eyJ0eXAiOiJKV1QiL...",
    "user_email": "admin@example.com",
    "user_nicename": "admin",
    "user_display_name": "admin"
}
```
---

## **2. API Endpoint to Change Roles**

```
POST /wp-json/ck/v1/change-role
```

### **Examples of a Valid Request**
#### **Email**
```json
curl -X POST https://yourwebsite.com/wp-json/ck/v1/change-role
     -H "Authorization: Bearer YOUR_JWT_TOKEN"
     -H "Content-Type: application/json"
     -d '{
           "email": "user@example.com",
           "role": "coolest_kid"
         }'
```
#### **Last and First Name**
```json
curl -X POST https://yourwebsite.com/wp-json/ck/v1/change-role
     -H "Authorization: Bearer YOUR_JWT_TOKEN"
     -H "Content-Type: application/json"
     -d '{
            "first_name": "Loreen",
            "last_name": "Friedl",
            "role": "cool_kid"
         }'
```

---

## **3. Expected Responses**

### **Success (200 OK)**
```json
{
    "message": "User role updated successfully.",
    "user_id": 12,
    "new_role": "cool_kid"
}
```
### **Common Errors**
| Code  | Description |
|-------|------------|
| `400` | Missing required parameters in the request. |
| `401` | Unauthorized (Invalid or missing authentication token). |
| `404` | User not found. |
| `500` | Internal server error. |

### **Example Error Response:**
```json
{
    "code": "user_not_found",
    "message": "The specified user was not found.",
    "data": {
        "status": 404
    }
}
```
