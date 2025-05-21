import React, { useState } from "react";
import { Link } from "react-router-dom";
import jobVisual from './assets/undraw_drag-and-drop_v4po.png';
import "./Homepage.css";

export default function HomePage() {
   const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  const toggleMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
  };
  return (
    <div className="homepage">
      <header className="navbar">
        <div className="navbar-left">
          <span className="logo">Trackflow</span>
        </div>
        <div className="mobile-menu-icon" onClick={toggleMenu}>
          &#9776;
        </div>
        <nav className={`navbar-right ${isMobileMenuOpen ? "open" : ""}`}>
          <Link to="/" onClick={toggleMenu}>Home</Link>
          <Link to="/AllJobs" onClick={toggleMenu}>All Jobs</Link>
          <Link to="/Auth" className="nav-button" onClick={toggleMenu}>Login/SignUP</Link>
          {/* <Link to="/Auth" className="nav-button" onClick={toggleMenu}>Sign up</Link> */}
        </nav>
      </header>

      <div className="main-section">
        <div className="left-section">
          <h1>Track Your Dream Job Opportunities Effortlessly</h1>
          <p>Stay organized, stay ahead. Manage your job applications with ease and confidence.</p>
          <Link to="/AllJobs" onClick={toggleMenu}><button className="cta-button">Start Tracking Jobs Now</button></Link>
        </div>
        <div className="right-section">
          <img src={jobVisual} alt="Visual Job Tracking" className="hero-visual" />
        </div>
      </div>

      <section className="features">
        <h2>Key Features</h2>
        <div className="features-grid">
          <div className="feature-card">
            <h3>Job Posts</h3>
            <p>Employers post jobs that match your interests and career path.</p>
          </div>
          <div className="feature-card">
            <h3>Application Tracking</h3>
            <p>Track each application with clear statuses and updates.</p>
          </div>
          <div className="feature-card">
            <h3>Job Search</h3>
            <p>Search smart. Filter by location, company, and position easily.</p>
          </div>
        </div>
      </section>

      <section className="testimonials">
        <h2>What Users Are Saying</h2>
        <div className="testimonial-card">
          <p>"This app helped me stay on top of my job search. Highly recommended!"</p>
          <span>- Alex, Software Developer</span>
        </div>
      </section>

      <section className="blog-preview">
        <h2>Latest from Our Blog</h2>
        <div className="blog-post">
          <h3>5 Tips for Job Hunting in 2025</h3>
          <p>Discover smart strategies to improve your job search this year.</p>
        </div>
      </section>

      <footer className="footer">
        <p>&copy; 2025 Trackflow. All rights reserved.</p>
        <div className="footer-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>
      </footer>
    </div>
  );
}
