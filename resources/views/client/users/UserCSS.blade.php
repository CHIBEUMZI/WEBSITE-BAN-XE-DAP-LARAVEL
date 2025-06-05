  <title>Thông tin cá nhân</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      padding: 20px;
      display: flex;
      justify-content: center;
    }
    .container {
      display: flex;
      gap: 40px;
      max-width: 900px;
      width: 100%;
    }
    .form-section {
      flex: 2;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }
    input, select, textarea, button {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }
    input:disabled {
      background-color: #f5f5f5;
    }
    .gender-options {
      display: flex;
      gap: 15px;
    }
    .gender-options input {
      width: auto;
    }
    .gender-options label {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .birthdate {
      display: flex;
      gap: 10px;
    }
    .side-section {
      flex: 1;
      text-align: center;
    }
    .side-section img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      background-color: #0070f0;
    }
    .upload-btn {
      margin-top: 10px;
      padding: 8px 15px;
      background-color: #fff;
      border: 1px solid #0070f0;
      color: #0070f0;
      border-radius: 8px;
      cursor: pointer;
    }
    .note {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }
    .submit-btn {
      background-color: #3e3eff;
      color: white;
      font-weight: bold;
      border: none;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      width: 200px;
    }
  </style>