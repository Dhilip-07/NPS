<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Generate NPS Reports for the year of 2023</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h2>Generate NPS Reports for the year of 2023</h2>
    <table class="table table-striped table-hover mt-4">
      <thead>
        <tr>
          <th>Sno</th>
          <th>Campign Name</th>
          <th>Campign Phone</th>
          <th>Campign created Name</th>
          <th>Campign Tenant Name</th>
          <th>Survey Name</th>
          <th>Question 1 Details</th>
          <th>Answer Rating Details</th>
          <th>Question 2 Details</th>
          <th>Other Answers</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($fulldata as $key => $completeData) { 
          $contactFirst = isset($completeData['contactData']['firstname']) ? $completeData['contactData']['firstname'] : '';
          $contactLast = isset($completeData['contactData']['lastname']) ? $completeData['contactData']['lastname'] : '';
          $contactMobile = isset($completeData['contactData']['contactFirst']) ? $completeData['contactData']['contactFirst'] : '';
          $userFirst = isset($completeData['userData']['firstname']) ? $completeData['userData']['firstname'] : '';
          $userLast = isset($completeData['userData']['lastname']) ? $completeData['userData']['lastname'] : '';
          $tenantName = isset($completeData['tenantData']['tenant_name']) ? $completeData['tenantData']['tenant_name'] : '';
          $CampianName = isset($completeData['surveyData']['campain_name']) ? $completeData['surveyData']['campain_name'] : '';
          $question1 = isset($completeData['questiondata1']['question_name']) ? $completeData['questiondata1']['question_name'] : '';
          $answer1 = isset($completeData['surveyresponseData']['answer_id']) ? $completeData['surveyresponseData']['answer_id'] : '';
          $question2 = isset($completeData['questiondata2']['question_name']) ? $completeData['questiondata2']['question_name'] : '';
          $answer2 = isset($completeData['surveyresponseData']['answer_id2']) ? $completeData['surveyresponseData']['answer_id2'] : '';
        ?>
        <tr>
          <td><?php echo $key; ?></td>
          <td><?php echo $contactFirst." ".$contactFirst; ?></td>
          <td><?php echo $contactFirst; ?></td>
          <td><?php echo $userFirst." ".$userLast; ?></td>
          <td><?php echo $tenantName; ?></td>
          <td><?php echo $CampianName; ?></td>
          <td><?php echo $question1; ?></td>
          <td><?php echo $answer1; ?></td>
          <td><?php echo $question2; ?></td>
          <td><?php echo $answer2; ?></td>
          <td><?php echo date("Y/m/d", strtotime($completeData['surveyresponseData']['created_at'])); ?></td>
        </tr>
      <?php } ?>        
      </tbody>
    </table>
  </div>
</body>
</html>