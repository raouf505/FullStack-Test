import React, { useState, useEffect } from "react";

function ConnectionList() {
  const [users, setUsers] = useState([]);
  const [currentUser, setCurrentUser] = useState([]);
  
  useEffect(() => {
    const fetchUsers = async () => {
      const response = await fetch(
        "http://localhost/quantic/wp-json/connections/v1/list"
      );
      const data = await response.json();
      setUsers(data);
    };

    fetchUsers();
  }, []);

  useEffect(() => {
    const fetchCurrentUser = async () => {
      const response = await fetch(
        "http://localhost/quantic/wp-json/connections/v1/current"
      );
      const data = await response.json();
      setCurrentUser(data);
    };

    fetchCurrentUser();
  }, []);

  return (
    <section className="page-section bg-light" id="users">
      <div className="container">
        <div className="text-center">
          <h2 className="section-heading text-uppercase">our amazing users</h2>
          <h3 className="section-subheading text-muted">
            Choose whom to send him your connection request.
          </h3>
        </div>
        <div className="row">
            {users.map((user) => (
              <div className="col-lg-4">
                <div className="user-member">
                  <img
                    className="mx-auto rounded-circle"
                    src="https://placehold.co/400/orange/white"
                    alt={user.display_name}
                  />
                  <h4>{user.display_name}</h4>
                  <a
                    className="btn btn-success mx-2"
                    href={'/add?id=' + user.id}
                    aria-label="{user.name} WordPress Profile"
                  >
                    ADD
                  </a>
                </div>
              </div>
            ))}
        </div>
      </div>
    </section>
  );
}

export default ConnectionList;
