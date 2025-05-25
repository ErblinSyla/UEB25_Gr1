INSERT INTO `contact_us` (`id`, `name`, `email`, `gender`, `age`, `interests`, `message`) VALUES
(1, 'Fatos Rama', 'fatosrama.fr@gmail.com', 'male', 20, 'Back End', 'I would like more information!');

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `age`, `phone_number`, `gender`, `role`, `username`, `password`) VALUES
(2, 'Admin', 'User', 'admin@example.com', 30, '1234567890', 'Male', 'admin', 'admin', '$2y$10$7D2BzTq0PvfjpUucAh.CKOVv1iBGEy5GCwIZAaPOzFoZDTFtRGzF2'),
(3, 'Student', 'User', 'student@example.com', 22, '9876543210', 'Female', 'student', 'student', '$2y$10$IokAkH6tTwpbktpOPb2w0u15IiYxNtDE.jCZHyt5atbzst10Yn3p6'),
(4, 'Erblin', 'Syla', 'erblin.sylaa@gmail.com', 19, '+38344801804', 'Male', 'student', 'ESyla', '$2y$10$Jao1239auCRMC8t3T2cWuucUdUWsqGMXP0z4dqvdmj62u9cGdp0WC'),
(5, 'Fatos', 'Rama', 'fatosrama.fr@gmail.com', 20, '+38345377108', 'Male', 'student', 'Wither188', '$2y$10$hwr8tXXR/OI8yryDp5L6A.YjIBFEkAOltSt5C9L8Z3aNd431o2YIu'),
(6, 'Diellart', 'Mulolli', 'diellart.mulolli@student.uni-pr.edu', 19, '+38349578714', 'Male', 'student', 'DSMFIEK', '$2y$10$TChWnL0JtOHy3kJu0rQeG.5qMPuHHcoFndEyVChRff6gf4z7LcOj6'),
(7,'Ema', 'Brown','Ema.Brown@gmail.com', 25, '+38349123456','Male','student','em4','$2y$10$Jao1239auCRMC8t3T2cWuucUdUWsqGMXP0z4dqvdmj62u9cGdp0WC'),
(8,'Thomas','Taylor','Thomas.Taylor@hotmail.com', 26, '+38349123457','Male','student','tom','$2y$10$Jao1239auCRMC8t3T2cWuucUdUWsqGMXP0z4dqvdmj62u9cGdp0WC'),
(9,'Hannah','Harris','Hannah.Harris@gmail.com', 27,'+38349123458','Female','student','hannah','$2y$10$Jao1239auCRMC8t3T2cWuucUdUWsqGMXP0z4dqvdmj62u9cGdp0WC');

INSERT INTO `courses` (`ID`, `Name`, `Photo`, `Description`) VALUES
(1, 'C++', 'images/cpplogo.png', 'C++ is a powerful, high-performance language often used for system software, game development, and applications requiring real-time performance.'),
(2, 'CSS', 'images/csslogo.png', 'CSS (Cascading Style Sheets) controls the styling of web pages. Learn how to design beautiful, responsive websites with layout, colors, and animations.'),
(3, 'GitHub', 'images/github logo.png', 'GitHub is a platform for version control and collaboration. Learn to manage your projects, track changes, and collaborate with other developers efficiently.'),
(4, 'HTML', 'images/htmllogo.png', 'HTML (HyperText Markup Language) is the backbone of every website. It structures web content, allowing you to create pages with text, images, and links.'),
(5, 'JavaScript', 'images/javascriptlogo4.png', 'JavaScript is the essential language for web development, enabling interactive and dynamic content on websites. Learn to bring your web pages to life!'),
(6, 'Python', 'images/pythonlogo.png', 'Python is a versatile programming language known for its simplicity and readability. Perfect for beginners and widely used in web development, data science, AI, and automation.'),
(7, 'PHP', 'images/phplogo.png', 'PHP (Hypertext Preprocessor) is a widely-used open-source scripting language primarily designed for web development. It runs on the server side and is embedded within HTML to create dynamic web pages,');

INSERT INTO `course_applications` (`id`, `user_id`, `name`, `email`, `course_id`, `file_name`, `file_type`, `file_data`, `application_date`, `status`) VALUES
(1, 5, 'Fatos Rama', 'fatosrama.fr@gmail.com', 1, 'default.svg', 'image/svg+xml', 0x3c73766720786d6c6e733d22687474703a2f2f7777772e77332e6f72672f323030302f737667222077696474683d223630707822206865696768743d2235367078222076696577426f783d223020302034393620343633222076657273696f6e3d22312e31223e0d0a3c7061746820643d224d2034322e3131322033302e353831204320312e3238322034312e3536312c20322e3438392039342e3236392c2034352e323635203136382e32343120432035302e383938203137372e3938312c2035302e383938203137372e3938312c2034392e353236203138322e373431204320392e333334203332322e3138332c203133382e373237203435392e3938352c203238332e353030203433312e3932302043203330342e353833203432372e3833332c203330342e333237203432382e3938322c203238372e313234203431352e3636312043203234302e393131203337392e3837392c203231302e343432203335322e3130382c203134302e303033203238312e35363520432039312e363834203233332e3137352c203135372e333731203130312e3535302c203230382e383434203134332e3631372043203233362e313138203136352e3930382c203233372e363135203135382e3735332c203139372e323034203139392e3234302043203136312e393038203233342e3630322c203136312e393038203233342e3630322c203139372e323034203236392e3535312043203330352e383239203337372e3130382c203338382e323732203433362e3337302c203433322e353030203433382e3638382043203439302e353337203434312e3733302c203439352e383439203338322e3938312c203434352e333333203239362e3735392043203434312e333833203239302e3031382c203434312e333833203239302e3031382c203434332e353931203238312e3235392043203437392e303738203134302e3436372c203335302e35353220362e3834392c203230372e3738392033362e3131342043203138372e3435372034302e3238312c203138372e3738382033392e3030392c203230332e3833382035312e3334302043203233342e3136362037342e3634302c203237322e303136203130372e3436302c203330312e393832203133362e3434322043203333332e313734203136362e3630392c20333939203233322e3436322c20333939203233332e343939204320333939203233342e3734332c203239392e383432203333342c203239382e353939203333342043203239372e353231203333342c203239322e383435203333302e3232302c203237342e363330203331342e3632352043203236302e373630203330322e3735302c203236302e373630203330322e3735302c203239352e343135203236382e3038352043203333302e303730203233332e3431392c203333302e303730203233332e3431392c203239332e323835203139362e3935332043203137302e3131302037342e3834362c2038352e3631382031382e3838302c2034322e3131322033302e35383122207374726f6b653d226e6f6e65222066696c6c3d2223333337346638222066696c6c2d72756c653d226576656e6f6464222f3e0d0a3c2f7376673e0d0a,CURRENT_TIMESTAMP,'pending'),
(2, 5, 'Fatos Rama', 'fatosrama.fr@gmail.com', 1, 'default.svg', 'image/svg+xml', 0x3c73766720786d6c6e733d22687474703a2f2f7777772e77332e6f72672f323030302f737667222077696474683d223630707822206865696768743d2235367078222076696577426f783d223020302034393620343633222076657273696f6e3d22312e31223e0d0a3c7061746820643d224d2034322e3131322033302e353831204320312e3238322034312e3536312c20322e3438392039342e3236392c2034352e323635203136382e32343120432035302e383938203137372e3938312c2035302e383938203137372e3938312c2034392e353236203138322e373431204320392e333334203332322e3138332c203133382e373237203435392e3938352c203238332e353030203433312e3932302043203330342e353833203432372e3833332c203330342e333237203432382e3938322c203238372e313234203431352e3636312043203234302e393131203337392e3837392c203231302e343432203335322e3130382c203134302e303033203238312e35363520432039312e363834203233332e3137352c203135372e333731203130312e3535302c203230382e383434203134332e3631372043203233362e313138203136352e3930382c203233372e363135203135382e3735332c203139372e323034203139392e3234302043203136312e393038203233342e3630322c203136312e393038203233342e3630322c203139372e323034203236392e3535312043203330352e383239203337372e3130382c203338382e323732203433362e3337302c203433322e353030203433382e3638382043203439302e353337203434312e3733302c203439352e383439203338322e3938312c203434352e333333203239362e3735392043203434312e333833203239302e3031382c203434312e333833203239302e3031382c203434332e353931203238312e3235392043203437392e303738203134302e3436372c203335302e35353220362e3834392c203230372e3738392033362e3131342043203138372e3435372034302e3238312c203138372e3738382033392e3030392c203230332e3833382035312e3334302043203233342e3136362037342e3634302c203237322e303136203130372e3436302c203330312e393832203133362e3434322043203333332e313734203136362e3630392c20333939203233322e3436322c20333939203233332e343939204320333939203233342e3734332c203239392e383432203333342c203239382e353939203333342043203239372e353231203333342c203239322e383435203333302e3232302c203237342e363330203331342e3632352043203236302e373630203330322e3735302c203236302e373630203330322e3735302c203239352e343135203236382e3038352043203333302e303730203233332e3431392c203333302e303730203233332e3431392c203239332e323835203139362e3935332043203137302e3131302037342e3834362c2038352e3631382031382e3838302c2034322e3131322033302e35383122207374726f6b653d226e6f6e65222066696c6c3d2223333337346638222066696c6c2d72756c653d226576656e6f6464222f3e0d0a3c2f7376673e0d0a,CURRENT_TIMESTAMP,'pending');

INSERT INTO `demographics` (`id`, `name`, `percentage`, `countries`, `comment`) VALUES
(2, 'Europe', 30, 'UK, Germany, France, Spain', 'Significant interest in technological education and development.'),
(3, 'Asia', 25, 'India, China, Japan', 'Growing user base driven by demand for coding skills.'),
(5, 'Africa', 3, 'Nigeria, South Africa, Kenya', 'Developing market with increasing digital adoption.'),
(6, 'Australia/Oceania', 2, 'Australia, New Zealand', 'Niche audience with steady engagement.'),
(8, 'North America', 35, 'USA, Canada', 'High engagement due to a strong tech culture.'),
(9, 'South America', 5, 'Brazil, Argentina, Colombia', 'Emerging interest in online tech courses.');

INSERT INTO `newsletter` (`user_id`, `email`) VALUES
(5, 'fatosrama.fr@gmail.com'),
(4, 'erblin2005@gmail.com');

INSERT INTO `pricing_plans` (`id`, `name`, `price`, `features`, `frequency`) VALUES
(2, 'Standard Plan', 200.00, 'Access to all courses, Priority Support', 'Monthly'),
(3, 'Premium Plan', 400.00, 'Access to all courses, Mentorship, Certification', 'Yearly'),
(4, 'Lifetime Plan', 800.00, 'Access to all courses forever, One-on-one coaching', 'One-time'),
(9, 'Basic Plan', 100.00, 'Access to basic courses, Community Support', 'One-time');

INSERT INTO `professors` (`ID`, `Name`, `Title`, `Gender`, `Biography`) VALUES
(1, 'John Doe', 'Professor', 'Male', 'John Doe is an expert in HTML, CSS and JavaScript, with over 10 years of experience.'),
(2, 'Alice Smith', 'Professor', 'Female', 'Alice Smith specializes in data structures and algorithms, with a strong background in competitive programming and 8 years of teaching experience.'),
(3, 'Bob Johnson', 'Professor', 'Male', 'Bob Johnson is a seasoned expert in database systems and SQL, having worked in both academia and industry for over 12 years.'),
(4, 'Carol Nguyen', 'Professor', 'Female', 'Carol Nguyen focuses on artificial intelligence and machine learning, and has been publishing research in the field for more than 9 years.'),
(5, 'David Lee', 'Professor', 'Male', 'David Lee has deep expertise in cybersecurity and network protocols, with 10 years of experience in both teaching and consulting.');

INSERT INTO `review` (`user_id`,`course_id`,`comment`,`rating`,`response`) VALUES
(7,1,'The programming academy offered a comprehensive curriculum. Thanks to their hands-on approach and expert instructors, I landed my first development job right after graduation.', 5, 'Response'),
(8,2,'I loved the interactive lessons and the real-world projects that helped me apply what I learned. The support from both instructors and fellow students was invaluable, and much appreciated!', 4, 'Thanks :)'),
(9,3,'The academys online resources and mentoring program made learning to code an enjoyable and structured experience.I appreciate the career support that helped me transition into the tech industry.', 4, NULL),
(6,4,'This website needs serious work , there are X bugs and Y features missing!', 2, NULL);

