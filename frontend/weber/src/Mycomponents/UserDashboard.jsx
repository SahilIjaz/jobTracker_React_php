import React, { useState, useEffect } from "react";
import axios from "axios";
import "./UserDashboard.css";

export default function UserDashboard() {
  const [email, setEmail] = useState("");
  const [appliedJobs, setAppliedJobs] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    const storedEmail = localStorage.getItem("email");
    if (storedEmail) {
      setEmail(storedEmail);
      fetchApplications(storedEmail);
    }
  }, []);

  const fetchApplications = async (emailToUse = email) => {
    if (!emailToUse) {
      setError("Email is missing. Please login again.");
      return;
    }

    setLoading(true);
    setError("");
    try {
      const response = await axios.post(
        "http://localhost:8000/api/applications-by-email",
        { email: emailToUse }
      );

      if (response.data && response.data.applications) {
        setAppliedJobs(response.data.applications);
      } else {
        setAppliedJobs([]);
      }
    } catch (err) {
      console.error(err);
      setError("Failed to load applications.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="user-dashboard">
      <h2 className="dashboard-title">Your Applications</h2>

      {loading && <p>Loading applications...</p>}
      {error && <p className="error">{error}</p>}

      <div className="application-list">
        {appliedJobs.length === 0 && !loading && <p>No applications found.</p>}
        {appliedJobs.map((job) => (
          <div key={job.id} className="application-card">
            <p><strong>Job Applied:</strong> {job.job_title}</p>
            <p><strong>Applicant Name:</strong> {job.applicant_name}</p>
            <p><strong>Applied on:</strong> {new Date(job.appliedAt).toLocaleString()}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
