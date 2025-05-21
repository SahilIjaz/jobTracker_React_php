import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./AllJobsPage.css";

export default function AllJobs() {
  const [jobs, setJobs] = useState([]);
  const [filterStatus, setFilterStatus] = useState("All");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [token, setToken] = useState("");

  const navigate = useNavigate();

  // Load token from localStorage on mount
  useEffect(() => {
    const storedToken = localStorage.getItem("token");
    setToken(storedToken || "");
  }, []);

  // Fetch jobs whenever filterStatus changes
  useEffect(() => {
    const fetchJobs = async () => {
      try {
        setLoading(true);
        const statusQuery = filterStatus === "All" ? "" : `?status=${filterStatus}`;
        const response = await axios.get(`http://localhost:8000/api/all-jobs${statusQuery}`);
        setJobs(response.data.jobs);
        setError("");
      } catch (err) {
        setError("Failed to fetch jobs. Please try again later.");
      } finally {
        setLoading(false);
      }
    };

    fetchJobs();
  }, [filterStatus]);

  // When Apply Job button clicked
  const handleApplyClick = (jobId) => {
    if (!token) {
      alert("You are not logged in. Please login to apply.");
      return;
    }

    localStorage.setItem("jobId", jobId); // Save job id in localStorage
    navigate("/apply-job"); // Navigate to application form
  };

  // Filter jobs by status
  const filteredJobs =
    filterStatus === "All"
      ? jobs
      : jobs.filter((job) => job.status.toLowerCase() === filterStatus.toLowerCase());

  return (
    <div className="all-jobs-page">
      <h2 className="page-title">All Job Listings</h2>

      <div className="filter-container">
        <label htmlFor="statusFilter">Filter by status:</label>
        <select
          id="statusFilter"
          value={filterStatus}
          onChange={(e) => setFilterStatus(e.target.value)}
        >
          <option value="All">All</option>
          <option value="open">Opened</option>
          <option value="closed">Closed</option>
        </select>
      </div>

      {loading ? (
        <p>Loading jobs...</p>
      ) : error ? (
        <p className="error">{error}</p>
      ) : filteredJobs.length === 0 ? (
        <p className="no-jobs">No jobs found for this status.</p>
      ) : (
        <div className="job-list">
          {filteredJobs.map((job) => (
            <div key={job.id} className="job-card">
              <h3>{job.jobTitle}</h3>
              <p><strong>Company:</strong> {job.companyName}</p>
              <p><strong>Location:</strong> {job.location}</p>
              <p><strong>Salary:</strong> {job.salary}</p>
              <p><strong>Description:</strong> {job.description}</p>
              <p><strong>Requirements:</strong> {job.requirements}</p>
              <p><strong>Status:</strong> {job.status}</p>
              <p><strong>Posted on:</strong> {new Date(job.created_at).toLocaleDateString()}</p>
              <button
                className="apply-button"
                onClick={() => handleApplyClick(job.id)}
              >
                Apply Job
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
