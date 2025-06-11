<?php
// generate_hash.php

// เปิดการแสดงผลข้อผิดพลาดสำหรับการพัฒนา (ควรปิดในการใช้งานจริง)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ระบุพาธไปยังไฟล์ PasswordHash.php ของคุณ
// ตรวจสอบให้แน่ใจว่าพาธนี้ถูกต้องเมื่อเทียบกับตำแหน่งของ generate_hash.php
require_once 'source/secure/PasswordHash.php'; 

// ******************************************************
// ตั้งค่ารหัสผ่านที่คุณต้องการแฮชตรงนี้
$password_to_hash = 'iydgtv'; 
// ******************************************************

// เรียกใช้ฟังก์ชัน create_hash เพื่อแฮชรหัสผ่าน
// ฟังก์ชัน create_hash() รับเพียงพารามิเตอร์เดียวคือ password เท่านั้น
$hashedPassword = create_hash($password_to_hash);

echo "<h2>Password Hashing Tool</h2>";
echo "Plain Text Password: <b>" . htmlspecialchars($password_to_hash) . "</b><br><br>";
echo "<h3>Generated Hashed Password:</h3>";
echo "<code>" . htmlspecialchars($hashedPassword) . "</code><br><br>";
echo "<i>คัดลอกค่าด้านบนนี้ไปวางในฟิลด์รหัสผ่านในฐานข้อมูล MySQL ของคุณได้เลย</i><br>";
echo "<i>โปรดทราบว่าค่าแฮชจะแตกต่างกันทุกครั้งที่คุณรันสคริปต์นี้ (เนื่องจากใช้ Salt แบบสุ่ม) ซึ่งเป็นเรื่องปกติและปลอดภัย</i>";

// ทดสอบการตรวจสอบรหัสผ่าน (ไม่จำเป็นต้องใช้ใน MySQL แต่แสดงให้เห็นว่าใช้งานได้)
echo "<hr><h3>Verification Test:</h3>";
$test_password = $password_to_hash; // ทดสอบด้วยรหัสผ่านเดิม
if (validate_password($test_password, $hashedPassword)) {
    echo "Verification for '" . htmlspecialchars($test_password) . "': <span style='color:green;'>Password matches the hash!</span><br>";
} else {
    echo "Verification for '" . htmlspecialchars($test_password) . "': <span style='color:red;'>Password DOES NOT match the hash!</span><br>";
}

// ทดสอบด้วยรหัสผ่านที่ไม่ถูกต้อง
$wrong_password = 'wrongpassword';
if (validate_password($wrong_password, $hashedPassword)) {
    echo "Verification for '" . htmlspecialchars($wrong_password) . "': <span style='color:green;'>Password matches the hash!</span><br>";
} else {
    echo "Verification for '" . htmlspecialchars($wrong_password) . "': <span style='color:red;'>Password DOES NOT match the hash!</span><br>";
}

?>