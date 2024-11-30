import React, { useState, useEffect } from "react";

function UserList() {
  const [users, setUsers] = useState([]);
  const [connections, setConnections] = useState([]);
  const [currentUser, setCurrentUser] = useState([]);

  useEffect(() => {
    const fetchUsers = async () => {
      const response = await fetch(
        "http://localhost/quantic/wp-json/connections/v1/users-list"
      );
      const data = await response.json();
      setUsers(data);
    };

    fetchUsers();
  }, []);

  useEffect(() => {
    const fetchCurrentUser = async () => {
      const response = await fetch(
        "http://localhost/quantic/wp-json/connections/v1/current",
        {
          credentials: "include", // Important for cookie-based auth
        }
      );
      const data = await response.json();
      setCurrentUser(data);
    };

    fetchCurrentUser();
  }, []);

  useEffect(() => {
    const fetchConnections = async () => {
      const response = await fetch(
        "http://localhost/quantic/wp-json/connections/v1/list",
        {
          credentials: "include", // Important for cookie-based auth
        }
      );
      const data = await response.json();
      setConnections(data);
    };

    fetchConnections();
  }, []);

  // Filter employees where country is 'Canada'
  const filteredUsersList = users.filter((user) => user.ID != currentUser.id);
  var filteredConnections = [];

   return (
    <section className="page-section bg-light" id="users">
      <div className="container">
        <div className="text-center">
          <h2 className="section-heading text-uppercase">our amazing users</h2>
          <h3 className="section-subheading text-muted">
            Choose whom to send him your connection request.
            {currentUser.id}
          </h3>
        </div>
        <div className="row">
          {filteredUsersList.map((user) => (
            <div className="col-lg-4">
              <div className="user-member">
                <img
                  className="mx-auto rounded-circle"
                  src="https://placehold.co/400/orange/white"
                  alt={user.display_name}
                />
                <h4>{user.display_name}</h4>
                {(filteredConnections.length = null)}
                <div style={{ display: "none" }}>
                  {filteredConnections = 
                    connections.filter(
                      (connection) =>
                        (connection.user_id_1 == currentUser.id &&
                          connection.user_id_2 == user.ID) ||
                        (connection.user_id_2 == currentUser.id &&
                          connection.user_id_1 == user.ID)
                    )
                  }
                </div>
                {filteredConnections.length > 0 ? (
                  filteredConnections.map((connection) =>
                    connection.user_id_1 == currentUser.id &&
                    connection.user_id_2 == user.ID ? (
                      <a
                        className="btn btn-warning mx-2 disabled"
                        href="#"
                        aria-label="{user.name} WordPress Profile"
                      >
                        SENT AND WAITING
                      </a>
                    ) : connection.user_id_2 == currentUser.id &&
                      connection.user_id_1 == user.ID ? (
                      <a
                        className="btn btn-info mx-2 btn-danger"
                        href={"http://localhost/quantic/wp-json/connections/v1/accept?connection_id=" + connection.id}
                        aria-label="{user.name} WordPress Profile"
                      >
                        ACCEPT
                      </a>
                    ) : (connection.user_id_1 == currentUser.id &&
                        connection.user_id_2 == user.ID) ||
                      (connection.user_id_2 == currentUser.id &&
                        connection.user_id_1 == user.ID &&
                        connection.status == "accepted") ? (
                      <a
                        className="btn btn-info mx-2 disabled"
                        href="#"
                        aria-label="{user.name} WordPress Profile"
                      >
                        YOU ARE FRIENDS
                      </a>
                    ) : null
                  )
                ) : (
                  <a
                    className="btn btn-success mx-2"
                    href={"http://localhost/quantic/wp-json/connections/v1/add?user_id_2=" + user.ID}
                    aria-label="{user.name} WordPress Profile"
                  >
                    ADD
                  </a>
                )}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

export default UserList;
