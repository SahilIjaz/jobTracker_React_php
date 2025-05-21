import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./RecruiterDashboard.css";

export default function RecruiterDashboard() {
  const [jobs, setJobs] = useState([]);
  const [editId, setEditId] = useState(null);
  const [editData, setEditData] = useState({});
  const [email, setEmail] = useState("");
  const [activeTab, setActiveTab] = useState("All");
  const [creatorId, setCreatorId] = useState(null);
  const navigate = useNavigate();

  // Fetch email and redirect unauthorized users
  useEffect(() => {
    const savedEmail = localStorage.getItem("email");
    const savedRole = localStorage.getItem("role");

    if (savedRole !== "recruiter") {
      navigate("/");
    }

    if (savedEmail) {
      setEmail(savedEmail);
    }
  }, [navigate]);

  // Fetch creator_id based on email
  useEffect(() => {
    const fetchCreatorId = async () => {
      try {
        const res = await axios.get(
          `http://localhost:8000/api/recruiter-id?email=${email}`
        );
        setCreatorId(res.data.creator_id);
      } catch (err) {
        console.error("Failed to fetch creator ID:", err);
      }
    };

    if (email) fetchCreatorId();
  }, [email]);

  // Fetch jobs created by the recruiter
  useEffect(() => {
    const fetchJobs = async () => {
      try {
        const res = await axios.get(
          `http://localhost:8000/api/jobs-by-creator?email=${email}`
        );
        const transformedJobs = res.data.jobs.map((job) => ({
          id: job.id,
          title: job.jobTitle,
          company: job.companyName,
          location: job.location,
          jobType: job.employmentType,
          description: job.description,
          status: job.status === "open" ? "Active" : "Closed",
          salary: job.salary || "",
          requirements: job.requirements || "",
        }));
        setJobs(transformedJobs);
      } catch (err) {
        console.error("Error fetching jobs:", err);
        setJobs([]);
      }
    };

    if (email) fetchJobs();
  }, [email]);

  const handleDelete = async (id) => {
    try {
      await axios.delete("http://localhost:8000/api/delete-job", {
        data: { job_id: id, email },
      });
      setJobs(jobs.filter((job) => job.id !== id));
    } catch (error) {
      console.error("Failed to delete job:", error);
    }
  };

  const handleEdit = (job) => {
    setEditId(job.id);
    setEditData(job);
  };

  const handleChange = (e) => {
    setEditData({ ...editData, [e.target.name]: e.target.value });
  };

  const handleUpdate = async (e) => {
    e.preventDefault();

    try {
      const updatedJob = {
        creator_id: creatorId,
        jobTitle: editData.title,
        employmentType: editData.jobType,
        location: editData.location,
        salary: editData.salary,
        companyName: editData.company,
        requirements: editData.requirements,
        description: editData.description,
        status: editData.status === "Active" ? "open" : "closed",
      };

      await axios.patch(
        `http://localhost:8000/api/update-job/${editId}`,
        updatedJob
      );

      setJobs(jobs.map((job) => (job.id === editId ? { ...editData } : job)));
      setEditId(null);
    } catch (error) {
      console.error("Failed to update job:", error.response?.data || error);
    }
  };

  const filteredJobs =
    activeTab === "All" ? jobs : jobs.filter((job) => job.status === activeTab);

  return (
    <div className="recruiter-dashboard">
      <h2 className="dashboard-title">Recruiter Dashboard</h2>

      {/* Job Post Button */}
      <button
        className="job-post-button"
        onClick={() => navigate("/JobPostForm")}
        style={{ marginBottom: "20px", padding: "10px 20px", fontSize: "16px" }}
      >
        Post New Job
      </button>

      <div className="tabs">
        {["All", "Active", "Closed"].map((tab) => (
          <button
            key={tab}
            className={`tab-button ${activeTab === tab ? "active" : ""}`}
            onClick={() => setActiveTab(tab)}
          >
            {tab}
          </button>
        ))}
      </div>

      <div className="jobs-list">
        {filteredJobs.length === 0 ? (
          <p>No jobs found.</p>
        ) : (
          filteredJobs.map((job) => (
            <div key={job.id} className="job-card">
              {editId === job.id ? (
                <form onSubmit={handleUpdate} className="edit-form">
                  <input
                    type="text"
                    name="title"
                    value={editData.title}
                    onChange={handleChange}
                    required
                  />
                  <input
                    type="text"
                    name="company"
                    value={editData.company}
                    onChange={handleChange}
                    required
                  />
                  <input
                    type="text"
                    name="location"
                    value={editData.location}
                    onChange={handleChange}
                    required
                  />
                  <input
                    type="text"
                    name="jobType"
                    value={editData.jobType}
                    onChange={handleChange}
                    required
                  />
                  <input
                    type="text"
                    name="salary"
                    value={editData.salary}
                    onChange={handleChange}
                  />
                  <textarea
                    name="requirements"
                    value={editData.requirements}
                    onChange={handleChange}
                  />
                  <textarea
                    name="description"
                    value={editData.description}
                    onChange={handleChange}
                    required
                  />
                  <select
                    name="status"
                    value={editData.status}
                    onChange={handleChange}
                    required
                  >
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                  </select>
                  <button type="submit">Update</button>
                </form>
              ) : (
                <>
                  <h3>{job.title}</h3>
                  <p><strong>Company:</strong> {job.company}</p>
                  <p><strong>Location:</strong> {job.location}</p>
                  <p><strong>Type:</strong> {job.jobType}</p>
                  <p><strong>Salary:</strong> {job.salary}</p>
                  <p><strong>Description:</strong> {job.description}</p>
                  <p><strong>Status:</strong> {job.status}</p>
                  <button onClick={() => handleEdit(job)}>Edit</button>
                  <button onClick={() => handleDelete(job.id)}>Delete</button>
                </>
              )}
            </div>
          ))
        )}
      </div>
    </div>
  );
}
