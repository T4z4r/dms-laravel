import './bootstrap';
import Chart from 'chart.js/auto';

// Handle edit department modal
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('[data-bs-target="#editDepartmentModal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            document.getElementById('edit_department_name').value = name;
            document.getElementById('editDepartmentForm').action = `/departments/${id}`;
        });
    });

    // Handle edit category modal
    const editCategoryButtons = document.querySelectorAll('[data-bs-target="#editCategoryModal"]');
    editCategoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            document.getElementById('edit_category_name').value = name;
            document.getElementById('editCategoryForm').action = `/categories/${id}`;
        });
    });

    // Confirm logout will be handled via CDN
});
