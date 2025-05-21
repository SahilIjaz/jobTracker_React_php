import React from "react";
import ReactDOM from "react-dom/client";
import { RouterProvider, createBrowserRouter } from "react-router-dom";
import App from "./App";
import Homepage from "./Mycomponents/Homepage";
import AllJobs from "./Mycomponents/AllJobs";
import Auth from "./Mycomponents/Auth";
import UserDashboard from "./Mycomponents/UserDashboard";
import RecruiterDashboard from "./Mycomponents/RecruiterDashboard";
import NavBar from "./Mycomponents/NavBar";
import ApplyJob from "./Mycomponents/ApplyJob";
import JobPostForm from "./Mycomponents/JobPostForm";
// import UserDashboard from "./Mycomponents/UserDashboard";
const router = createBrowserRouter([
  {
    path: "/",
    element: <App />,
  },
  {
    path: "/AllJobs",
    element: (
      <>
        <NavBar />
        <AllJobs />
      </>
    ),
  },
  {
    path: "/Auth",
    element: (
      <>
        <Auth />
      </>
    ),
  },
  {
    path: "/user-dashboard",
    element: (
      <>
        <NavBar />
        <UserDashboard />
      </>
    ),
  },
  // {
  //   path: "/dashboard",
  //   element: (
  //     <>
  //       <UserDashboard />
  //     </>
  //   ),
  // },
  {
    path: "/apply-job",
    element: (
      <>
        <ApplyJob />
        {/* <NavBar /> */}
        {/* <UserDashboard /> */}
      </>
    ),
  },
  {
    path: "/recruiter-dashboard",
    element: (
      <>
        <NavBar />
        <RecruiterDashboard />
      </>
    ),
  },
  {
    path: "/JobPostForm",
    element: (
      <>
        <JobPostForm />
      </>
    ),
  },
]);
ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
);
