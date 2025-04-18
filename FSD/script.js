
document.addEventListener("DOMContentLoaded", function () {
    fetchSubjects();
});

async function fetchSubjects() {
    try {
        const response = await fetch("fetch_subjects.php");
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const subjects = await response.json();
        const container = document.getElementById("subjectsContainer");
        container.innerHTML = "";

        if (!Array.isArray(subjects) || subjects.length === 0) {
            container.innerHTML = "<p>No subjects available.</p>";
            return;
        }
        //adding subject buttons
        subjects.forEach(subject => {
            if (!subject.s_no || !subject.sub_name) return;

            const button = document.createElement("button");
            button.textContent = subject.sub_name;
            button.classList.add("subject-btn");
            button.dataset.subjectId = subject.s_no;
            button.dataset.subjectName = subject.sub_name;
            button.addEventListener("click", () => toggleQuestions(subject.s_no, subject.sub_name));
            container.appendChild(button);   
        });
    } catch (error) {
        console.error("Error fetching subjects:", error);
        document.getElementById("subjectsContainer").innerHTML = `<p>Error loading subjects: ${error.message}</p>`;
    }
}
let currentSubjectId = null;

async function toggleQuestions(subjectId, subjectName) {
    const container = document.getElementById("questionsContainer");
    // Hide questions if the same subject is clicked again
    if (currentSubjectId === subjectId) {
        container.innerHTML = "";
        currentSubjectId = null;
        return;
    }

    currentSubjectId = subjectId;
    try {
        const response = await fetch(`fetch_questions.php?subject_id=${subjectId}`);
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();
        const questions = data.questions;

        container.innerHTML = `<h3>${subjectName}</h3>`;

        if (!Array.isArray(questions) || questions.length === 0) {
            container.innerHTML += "<p>No questions available.</p>";
            return;
        }

        const form = document.createElement("form");
        form.method = "POST";
        form.action = "./api/submit_feedback.php";

        const hiddenField = document.createElement("input");
        hiddenField.type = "hidden";
        hiddenField.name = "subject_id";
        hiddenField.value = subjectId;
        form.appendChild(hiddenField);

        let totalQuestions = questions.length;

        // Create feedback table
        const table = document.createElement("table");
        table.classList.add("feedback-table");

        // Table headers
        const thead = document.createElement("thead");
        thead.innerHTML = `
            <tr>
                <th>Question</th>
                <th>OutStanding</th>
                <th>Very Good</th>
                <th>Good</th>
                <th>Ok</th>
            </tr>
        `;
        table.appendChild(thead);

        const tbody = document.createElement("tbody");

        questions.forEach(question => {
            if (!question.s_no || !question.Question) return;

            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${question.Question}</td>
                <td><input type="radio" name="feedback[${question.s_no}]" value="5"></td>
                <td><input type="radio" name="feedback[${question.s_no}]" value="4"></td>
                <td><input type="radio" name="feedback[${question.s_no}]" value="3"></td>
                <td><input type="radio" name="feedback[${question.s_no}]" value="1"></td>
            `;
            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        form.appendChild(table);

        // Add feedback notes
        const notesLabel = document.createElement("label");
        notesLabel.textContent = "student notes:";
        notesLabel.classList.add("feedback-label");

        const notesTextarea = document.createElement("textarea");
        notesTextarea.name = "techniques_notes";
        notesTextarea.classList.add("feedback-notes");
        form.appendChild(notesLabel);
        form.appendChild(notesTextarea);

        // Add submit button
        const submitButton = document.createElement("button");
        submitButton.type = "submit";
        submitButton.textContent = "Submit Feedback";
        submitButton.classList.add("submit-btn");
        submitButton.name = "submit";
        submitButton.disabled = true;

        form.appendChild(submitButton);
        container.appendChild(form);

        // Track answered questions
        const radioButtons = form.querySelectorAll("input[type=radio]");
        
        radioButtons.forEach(radio => {
            radio.addEventListener("change", () => checkAllAnswered(form, totalQuestions, submitButton));
        });
        submitButton.addEventListener("click", () =>{
            subjects_submitted.push(subjectId);
        });

    } catch (error) {
        console.error("Error fetching questions:", error);
        document.getElementById("questionsContainer").innerHTML = `<p>Error loading questions: ${error.message}</p>`;
    }
}

function checkAllAnswered(form, totalQuestions, submitButton) {
    const answeredQuestions = form.querySelectorAll("input[type=radio]:checked").length;
    submitButton.disabled = answeredQuestions !== totalQuestions;
}
//


