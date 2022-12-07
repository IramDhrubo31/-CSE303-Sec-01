
function myfunction() {
    var x = document.getElementById("pass");

    if (x.type === "password") {
        x.type = "text";
    }
    else {
        x.type = "password";
    }
}
function validate() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("pass").value;

    if (username == "Faculty" && password == "1234") {
        alert("Login Succesfull");
        window.location.replace("homepage.html");
        return false;
    }
    else if (username == "Student" && password == "4321") {
        alert("Login Succesfull");
        window.location.replace("Stud_homepage.html");
        return false;
    }
    else if (username == "H.auth" && password == "12345") {
        alert("Login Succesfull");
        window.location.replace("higher_auth_dashboard.html");
        return false;
    }
    else if (username == "Dept.head" && password == "54321") {
        alert("Login Succesfull");
        window.location.replace("Dept.head_dashboard.html");
        return false;
    }
    else {
        alert("Login Failed");
    }
}

    $("#enterButton").click(function() {
        let placeholders = [
            "Assessment Name",
            "Question Number",
            "TotalMarks",
            "Weightage",
        ];
        let courseName = document.getElementById('course').value;
        let noOfAssess= document.getElementById('anum').value;
        let section = document.getElementById('section').value;
        let semester = document.getElementById('semester').value;



        let assessmentSection = document.getElementById('assessmentSection');

        console.log(courseName)

        assessmentSection.innerHTML = '';

        let formTable = document.createElement('table');
        formTable.className = 'form-table';
        formTable.innerHTML = `
                            {% csrf_token %}
                            <input type="hidden" name="course" value="${courseName}" />
                            <input type="hidden" name="section" value="${section}" />
                             <input type="hidden" name="semester" value="${semester}" />
                        `;
        for (let i = 0; i < parseInt(noOfAssess); i++){
            let inputForm = document.createElement('tr')
            inputForm.innerHTML = `
            <td class="form-field">
               <input type="text" class ="form-control" id="questions${i + 1}" name="questions" class="form-control" placeholder="Question Number"/>
            </td>
            <td class="form-field" style="width: 180px;padding-right:20px;">
                <input type="text" id="assessmentName${i + 1}" name="assessmentName" class="form-control" placeholder="AssessmentName"/>
            </td>
            <td class="form-field" style="padding-right:20px;">
                <input type="text" id="totalMark${i + 1}" name="totalMarks" class="form-control" placeholder="Total Marks" />
            </td>
            <td class="form-field">
                <input type="text" id="weightAge${i + 1}" name="weightAge" class="form-control" placeholder="Weightage" />
            </td>
             <td class="form-field" style="text-align: center;padding-right: 20px;">
                    <select  class="form-select" id="co" name="co">
                       <option value="CO1">CO1</option>
                        <option value="CO2">CO2</option>
                         <option value="CO3">CO3</option>
                        <option value="CO4">CO4</option>
                    </select>
              </td>
            `;
            formTable.appendChild(inputForm);
        }
        let inputButton = document.createElement('tr');
        inputButton.innerHTML = `<td style="text-align: center;" class="last-row mt-2" colspan="4"><button class="btn btn-primary">Submit Assessment</button></td>`;
        formTable.appendChild(inputButton);
        assessmentSection.appendChild(formTable);
        return false;
});
