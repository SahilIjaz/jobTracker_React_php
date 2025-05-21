import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import './JobPostForm.css';
import {
  FaBuilding,
  FaMapMarkerAlt,
  FaRegFileAlt,
  FaClipboardList,
  FaBriefcase,
  FaSuitcase,
  FaEnvelope,
} from 'react-icons/fa';

export default function JobPostForm() {
  const [formData, setFormData] = useState({
    jobTitle: '',
    companyName: '',
    location: '',
    employmentType: '',
    salary: '',
    description: '',
    requirements: '',
    status: 'open', // default status
    email: '', // new field
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    try {
      const response = await axios.post('http://localhost:8000/api/create-job', formData);
      setSuccess('Your job has been posted successfully!');
      setFormData({
        jobTitle: '',
        companyName: '',
        location: '',
        employmentType: '',
        salary: '',
        description: '',
        requirements: '',
        status: 'open',
        email: '',
      });
      navigate('/recruiter-dashboard');
    } catch (err) {
      setError('Failed to post job. Please try again later.');
    }
    setLoading(false);
  };

  return (
    <div className="job-form-page">
      <div className="job-form-container">
        <h2 className="form-title">Create a Job Post</h2>
        <form className="job-form" onSubmit={handleSubmit}>
          <div className="input-group">
            <FaBriefcase className="icon" />
            <input
              type="text"
              name="jobTitle"
              required
              placeholder=" "
              value={formData.jobTitle}
              onChange={handleChange}
            />
            <label>Job Title</label>
          </div>

          <div className="input-group">
            <FaBuilding className="icon" />
            <input
              type="text"
              name="companyName"
              required
              placeholder=" "
              value={formData.companyName}
              onChange={handleChange}
            />
            <label>Company Name</label>
          </div>

          <div className="input-group">
            <FaMapMarkerAlt className="icon" />
            <input
              type="text"
              name="location"
              required
              placeholder=" "
              value={formData.location}
              onChange={handleChange}
            />
            <label>Location</label>
          </div>

          <div className="input-group">
            <FaEnvelope className="icon" />
            <input
              type="email"
              name="email"
              required
              placeholder=" "
              value={formData.email}
              onChange={handleChange}
            />
            <label>Creator's Email</label>
          </div>

          <div className="input-group select-group">
            <FaSuitcase className="icon" />
            <select
              name="employmentType"
              required
              value={formData.employmentType}
              onChange={handleChange}
            >
              <option value="" disabled>
                Select Job Type
              </option>
              <option value="Full-Time">Full-Time</option>
              <option value="Part-Time">Part-Time</option>
              <option value="Remote">Remote</option>
              <option value="Contract">Contract</option>
              <option value="Internship">Internship</option>
            </select>
          </div>

          <div className="input-group">
            <FaBriefcase className="icon" />
            <input
              type="text"
              name="salary"
              required
              placeholder=" "
              value={formData.salary}
              onChange={handleChange}
            />
            <label>Salary</label>
          </div>

          <div className="input-group textarea-group">
            <FaRegFileAlt className="icon" />
            <textarea
              name="description"
              required
              placeholder=" "
              value={formData.description}
              onChange={handleChange}
            ></textarea>
            <label>Job Description</label>
          </div>

          <div className="input-group textarea-group">
            <FaClipboardList className="icon" />
            <textarea
              name="requirements"
              required
              placeholder=" "
              value={formData.requirements}
              onChange={handleChange}
            ></textarea>
            <label>Requirements</label>
          </div>

          <button type="submit" className="post-btn" disabled={loading}>
            {loading ? 'Posting...' : 'Post Job'}
          </button>
        </form>
        {error && <p className="error">{error}</p>}
        {success && <p className="success">{success}</p>}
      </div>
 </div>
);
}