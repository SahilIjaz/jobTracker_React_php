import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./ApplyJob.css";

const JobApplicationForm = () => {
  const navigate = useNavigate();
  const [jobId, setJobId] = useState("");

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    contact: "",
    resume: "",
    experience: "",
    expectations: "",
  });

  useEffect(() => {
    const savedJobId = localStorage.getItem("jobId");
    if (savedJobId) {
      setJobId(savedJobId);
      console.log("Loaded jobId from localStorage:", savedJobId);
    }
  }, []);

  const handleChange = (e) => {
    setFormData((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!jobId) {
      alert("No job selected. Please go back and select a job.");
      return;
    }

    const requestBody = {
      job_id: jobId,
      name: formData.name,
      email: formData.email,
      contactNumber: formData.contact,
      resumeLink: formData.resume,
      experience: formData.experience,
      expectations: formData.expectations,
    };

    console.log("Submitting application for job ID:", jobId);
    console.log("Request Body:", JSON.stringify(requestBody, null, 2));

    try {
      const response = await axios.post(
        "http://localhost:8000/api/apply-job",
        requestBody,
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      if (response.data.status === 200) {
        alert("Application submitted successfully!");
        console.log("Server response:", response.data);

        localStorage.removeItem("jobId");

        setFormData({
          name: "",
          email: "",
          contact: "",
          resume: "",
          experience: "",
          expectations: "",
        });

        navigate("/user-dashboard"); // Redirect after success
      }
    } catch (error) {
      console.error(
        "Error submitting application:",
        error.response?.data || error.message
      );
      alert("Failed to submit application. Please try again later.");
    }
  };

  return (
    <div className="job-form-bg">
      <div className="form-container">
        <h2>Job Application Form</h2>

        {jobId ? (
          <p>
            Applying for Job ID: <strong>{jobId}</strong>
          </p>
        ) : (
          <p style={{ color: "red" }}>
            No job selected. Please go back and select a job.
          </p>
        )}

        <form onSubmit={handleSubmit}>
          <label>
            Name:
            <input
              type="text"
              name="name"
              required
              value={formData.name}
              onChange={handleChange}
            />
          </label>

          <label>
            Email:
            <input
              type="email"
              name="email"
              required
              value={formData.email}
              onChange={handleChange}
            />
          </label>

          <label>
            Contact Number:
            <input
              type="tel"
              name="contact"
              required
              value={formData.contact}
              onChange={handleChange}
            />
          </label>

          <label>
            Resume Link:
            <input
              type="url"
              name="resume"
              required
              value={formData.resume}
              onChange={handleChange}
            />
          </label>

          <label>
            Experience:
            <textarea
              name="experience"
              rows="4"
              required
              value={formData.experience}
              onChange={handleChange}
            />
          </label>

          <label>
            Expectations:
            <textarea
              name="expectations"
              rows="3"
              required
              value={formData.expectations}
              onChange={handleChange}
            />
          </label>

          <button type="submit" disabled={!jobId}>
            Apply Now
          </button>
        </form>
      </div>
    </div>
  );
};

export default JobApplicationForm;
