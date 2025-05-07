// Modal functionality for VAS Fixed Plan Modals
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const createPlanModal = document.getElementById('createPlanModal');
    const viewPlanModal = document.getElementById('viewPlanModal');
    const createPlanBtn = document.querySelector('button.bg-\\[\\#0018A8\\].font-bold');
    
    // Open the Create Plan modal when the main button is clicked
    if (createPlanBtn) {
        createPlanBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openCreatePlanModal();
        });
    }
    
    // Set default date to 3 months from now
    const planMaturityInput = document.getElementById('planMaturity');
    if (planMaturityInput) {
        const defaultDate = new Date();
        defaultDate.setMonth(defaultDate.getMonth() + 3);
        
        // Format date as YYYY-MM-DD for input field
        const formattedDate = defaultDate.toISOString().split('T')[0];
        planMaturityInput.value = formattedDate;
    }
});

// Function to open the Create Plan modal
function openCreatePlanModal() {
    const modal = document.getElementById('createPlanModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden'); // Prevent scrolling of background
}

// Function to close the Create Plan modal
function closeCreateModal() {
    const modal = document.getElementById('createPlanModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Function to show the View Plan modal
function showViewPlanModal() {
    // Get values from the Create Plan modal
    const amount = document.getElementById('planAmount').value;
    const planName = document.getElementById('planName').value || 'Savings Plan';
    const maturityDate = document.getElementById('planMaturity').value;
    
    // Validate inputs
    if (!amount || isNaN(parseFloat(amount)) || parseFloat(amount) <= 0) {
        alert('Please enter a valid amount');
        return;
    }
    
    if (!maturityDate) {
        alert('Please select a maturity date');
        return;
    }
    
    // Format the amount with commas
    const formattedAmount = parseFloat(amount).toLocaleString('en-NG');
    
    // Format the date in a more readable format (DD/MM/YYYY)
    const dateParts = maturityDate.split('-');
    const formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    
    // Calculate interest (30% per annum)
    const startDate = new Date();
    const endDate = new Date(maturityDate);
    const daysInYear = 365;
    const daysDifference = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
    const interestRate = 0.3; // 30% annual interest
    
    const interestEarned = parseFloat(amount) * interestRate * (daysDifference / daysInYear);
    const totalAmount = parseFloat(amount) + interestEarned;
    
    // Update the View Plan modal with these values
    document.getElementById('viewAmount').value = `${formattedAmount}`;
    document.getElementById('viewPlanName').value = planName;
    document.getElementById('viewMaturityDate').value = formattedDate;
    document.getElementById('viewInterestEarned').textContent = `₦${interestEarned.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    document.getElementById('viewTotalAmount').textContent = `₦${totalAmount.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    
    // Hide Create Plan modal and show View Plan modal
    document.getElementById('createPlanModal').classList.add('hidden');
    document.getElementById('viewPlanModal').classList.remove('hidden');
}

// Function to go back to the Create Plan modal
function goBackToCreateModal() {
    document.getElementById('viewPlanModal').classList.add('hidden');
    document.getElementById('createPlanModal').classList.remove('hidden');
}

// Function to confirm the plan (submit to backend)
function confirmPlan() {
    // Get values for submission
    const amount = document.getElementById('planAmount').value;
    const planName = document.getElementById('planName').value || 'Savings Plan';
    const maturityDate = document.getElementById('planMaturity').value;
    
    // Prepare data for submission
    const planData = {
        amount: parseFloat(amount),
        name: planName,
        maturity_date: maturityDate
    };
    
    // Submit data to backend using fetch API
    fetch('/vasfixed/create-plan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(planData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            document.getElementById('viewPlanModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            
            // Show success message or redirect
            alert('Plan created successfully!');
            
            // Optional: Reload the page to show the new plan
            window.location.reload();
        } else {
            alert('There was an error creating your plan. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error processing your request. Please try again.');
    });
}