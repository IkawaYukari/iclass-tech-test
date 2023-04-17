<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
<div>
    <div class="row">
        <div class="header">Department</div>
        <div class="header">Name</div>
        <div class="header">Salary</div>
        <div class="header">Served for</div>
    </div>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "edu";
    $dbname = "employees";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    $sql = "select m.dept_name, m.name, m.gender, m.salary, m.serve_for, 
s.employees_count, s.employees_salary from
(select m.dept_no, m.emp_no, d.dept_name, e.gender, s.salary, m.from_date, 
floor(datediff(now(), `hire_date`) / 365) as serve_for, 
concat(e.first_name,' ',e.last_name) as name
from dept_manager m left join departments d on m.dept_no = d.dept_no
left join employees e on m.emp_no = e.emp_no
left join salaries s on m.emp_no = s.emp_no
where m.`to_date` > now() and s.`to_date` > now()) as m
left join
(select e.dept_no,count(e.emp_no) as employees_count, 
sum(s.salary) as employees_salary
from 
(select emp_no, dept_no from dept_emp where `to_date` > now()) as e
left join 
(select emp_no, salary from salaries where `to_date` > now()) as s on e.emp_no = s.emp_no
group by e.dept_no) as s
on m.dept_no = s.dept_no
order by m.from_date asc";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="row gen' . $row['gender'] . '" onmousemove="popup(event)">
            <span class="popup">'
                .$row['employees_count'] . ' employees under this manager </br>$' . $row['employees_salary'] . ' spend on them totally
            </span>
            <div class="">' . $row['dept_name'] . '</div>
            <div class="">' . $row['name'] . '</div>
            <div class="num">' . $row['salary'] . '</div>
            <div class="num">' . $row['serve_for'] . 'Years</div>
            </div>';

        }
    }
    $conn->close();
    ?>
</div>
</body>
</html>