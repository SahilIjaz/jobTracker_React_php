import React, { useState } from 'react';
import { Link } from "react-router-dom";
import './NavBar.css';

const Navbar = () => {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  const toggleMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
  };

  return (
    <div>
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

        </nav>
      </header>
    </div>
  );
};

export default Navbar;