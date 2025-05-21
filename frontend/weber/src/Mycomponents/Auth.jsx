import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./Auth.css";

const SignupLogin = () => {
  const [isLogin, setIsLogin] = useState(true);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    gender: "",
    role: "user",
  });

  const navigate = useNavigate();

  const toggleForm = () => {
    setIsLogin((prev) => !prev);
    setFormData({
      name: "",
      email: "",
      password: "",
      gender: "",
      role: "user",
    });
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!formData.email || !formData.password) {
      alert("Email and password are required.");
      return;
    }

    if (!isLogin && (!formData.name || !formData.gender || !formData.role)) {
      alert("Please fill in all signup fields.");
      return;
    }

    try {
      let response;

      if (isLogin) {
        response = await axios.post("http://localhost:8000/api/login", {
          email: formData.email,
          password: formData.password,
        });
      } else {
        response = await axios.post("http://localhost:8000/api/create-user", formData);
      }

      const userData = response.data?.data;
      const token = response.data?.token;
      const userRole = userData?.role || formData.role;

      // Store user data in localStorage
      localStorage.setItem("email", userData?.email || formData.email);
      localStorage.setItem("role", userRole);
      localStorage.setItem("token", token);

      alert(response.data.message || (isLogin ? "Login successful!" : "Signup successful!"));

      // Navigate based on role
      if (userRole === "user") {
        navigate("/user-dashboard");
      } else if (userRole === "recruiter") {
        navigate("/recruiter-dashboard");
      } else {
        alert("Unknown role. Please contact support.");
      }

    } catch (error) {
      const msg =
        error?.response?.data?.message ||
        "Something went wrong. Please try again.";
      alert(msg);
      console.error("Auth error:", error);
    }
  };

  return (
    <div className="auth-page">
      <div className="auth-card">
        <h2>{isLogin ? "Welcome Back" : "Create Your Account"}</h2>
        <form onSubmit={handleSubmit} className="auth-form">
          {!isLogin && (
            <>
              <input
                type="text"
                name="name"
                placeholder="Full Name"
                value={formData.name}
                onChange={handleChange}
                required
              />
              <select
                name="gender"
                value={formData.gender}
                onChange={handleChange}
                required
              >
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </>
          )}

          <input
            type="email"
            name="email"
            placeholder="Email"
            value={formData.email}
            onChange={handleChange}
            required
          />

          <input
            type="password"
            name="password"
            placeholder="Password"
            value={formData.password}
            onChange={handleChange}
            required
          />

          {!isLogin && (
            <select
              name="role"
              value={formData.role}
              onChange={handleChange}
              required
            >
              <option value="user">User</option>
              <option value="recruiter">Recruiter</option>
            </select>
          )}

          <button type="submit" className="auth-button">
            {isLogin ? "Login" : "Sign Up"}
          </button>
        </form>

        <p className="toggle-text">
          {isLogin ? "Don't have an account?" : "Already have an account?"}{" "}
          <span onClick={toggleForm} className="toggle-link">
            {isLogin ? "Sign up" : "Login"}
          </span>
        </p>
      </div>
    </div>
  );
};

export default SignupLogin;
