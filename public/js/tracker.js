document.addEventListener('DOMContentLoaded', () => {
    // Select the expense form (ensure the selector matches your form)
    const expenseForm = document.getElementById('expense-form');
    if (!expenseForm) {
        console.error("Expense form not found! Please ensure the form has id='expense-form'.");
        return;
    }

    expenseForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Gather form data using FormData API
        const formData = new FormData(expenseForm);
        const expenseData = {
            date: formData.get('date'),
            name: formData.get('name'),
            type: formData.get('type'),
            price: formData.get('price')
        };

        // Log the data (for debugging)
        console.log("Sending JSON:", expenseData);

        // Send the data using Fetch API
        fetch('addExpense', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(expenseData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Instead of updating the DOM manually,
                    // reload the page to load the tracker page with the new expense table.
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.error || data.message));
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('An error occurred while adding the expense.');
            });
    });
});
