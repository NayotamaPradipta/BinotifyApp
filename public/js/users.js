document.addEventListener("DOMContentLoaded", function(){ 
    const userList = document.getElementById('user-list');
    const paginationDiv = document.getElementById('pagination');
    let currentPage = 1; 

    function fetchUsers(page = 1){ 
        fetch(`/api/users.php?page=${page}`)
        .then(response => response.json())
        .then(data => { 
            displayUsers(data.items);
            displayPagination(data.total_pages, data.current_page);
        });
    }

    function displayUsers(users){ 
        userList.innerHTML = '';
        users.forEach(user => {
            const userDiv = document.createElement('div');
            userDiv.classList.add('users');
            userDiv.innerHTML = `
                <div class="user-id">
                    <h2>${user.user_id}</h2>
                </div>
                <div class="user-detail">
                    <div class="user-info">
                        <span class="label">Username:</span>
                        <span class="value">${user.username}</span>
                    </div>
                    <div class="user-info">
                        <span class="label">Email:</span>
                        <span class="value">${user.email}</span>
                    </div>
                </div>
            `;
            userList.appendChild(userDiv);
        })
    }

    function displayPagination(total_pages, current_page){ 
        paginationDiv.innerHTML = ''; 
        const startPage = Math.max(1, currentPage-1); 
        const endPage = Math.min(total_pages, current_page + 1); 

        for (let i = startPage; i <= endPage; i++){ 
            const pageLink = document.createElement('a'); 
            pageLink.href = '#'; 
            pageLink.textContent = i; 
            pageLink.classList.add('pagination-link'); 
            if (i === currentPage){  
                pageLink.classList.add('active'); 
            }
            pageLink.addEventListener('click', function(e) {
                e.preventDefault(); 
                fetchUsers(i); 
            }); 
            paginationDiv.appendChild(pageLink);
        }
    }

    fetchUsers(currentPage); 
});