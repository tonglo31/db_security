SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";

-- INSTALL PLUGIN audit_log SONAME 'audit_log.so';

--
-- Database: `security_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `artwork`
--

CREATE TABLE `artwork` (
  `CID` varchar(400) NOT NULL,
  `id` int(11) NOT NULL,
  `artwork_name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `artwork`
--

INSERT INTO `artwork` (`CID`, `id`, `artwork_name`, `description`, `time`) VALUES
('bafkreiamt6wbhbcoc4eoxaiiwrcyjsa62awhohti4lfjsxqy5vdkzgz4ya', 7, 'Test', 'Test ', '2022-04-12T14:10:51.378+00:00'),
('bafkreidpalo45ihizqgqjqx4ff2nskgrtcprfvxtyiad2hkxpvpasri5g4', 3, 'Nothing we can do', 'Revision ', '2022-04-12T19:17:15.106+00:00'),
('bafkreigoqduwnuwgkt4a65ai2fgb4iuxehusyqruuuabh3h6jgeafb6b3m', 3, 'Flower', 'One Flower. Two Flower. Three Flower. Four Flower. Five Flower. ', '2022-04-12T19:14:36.986+00:00'),
('bafkreihmrc7rrixzoe57db2xpdhfe7uzrgq2py3mq7b2s6k4fqjb6jdeiq', 3, 'Bird ', 'Bird is Bird nothing special ', '2022-04-12T19:15:19.617+00:00'),
('bafybeiakxfeavacloojfp5cutvwmdjsvsffba3eqhylhanngq2zzx6npr4', 3, 'Paint1', 'England ', '2022-04-12T19:34:03.619+00:00'),
('bafybeigc734wb4zkvpunwsoza44uwhbo3bdjy6hewsmjgl26exibggn2ga', 3, 'People', '@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ ', '2022-04-12T19:23:19.697+00:00');

-- --------------------------------------------------------

--
-- Table structure for table `own_artwork`
--

CREATE TABLE `own_artwork` (
  `own_artwork_id` int(11) NOT NULL,
  `CID` varchar(400) NOT NULL,
  `id` int(11) NOT NULL,
  `salt` varchar(22) NOT NULL,
  `cid_hash` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `own_artwork`
--

INSERT INTO `own_artwork` (`own_artwork_id`, `CID`, `id`, `salt`, `cid_hash`) VALUES
(8, 'bafkreihmrc7rrixzoe57db2xpdhfe7uzrgq2py3mq7b2s6k4fqjb6jdeiq', 3, '', ''),
(9, 'bafkreidpalo45ihizqgqjqx4ff2nskgrtcprfvxtyiad2hkxpvpasri5g4', 3, '', ''),
(10, 'bafybeiakxfeavacloojfp5cutvwmdjsvsffba3eqhylhanngq2zzx6npr4', 3, '', ''),
(11, 'bafkreigoqduwnuwgkt4a65ai2fgb4iuxehusyqruuuabh3h6jgeafb6b3m', 3, '', ''),
(12, 'bafybeigc734wb4zkvpunwsoza44uwhbo3bdjy6hewsmjgl26exibggn2ga', 3, '', ''),
(19, 'bafkreiamt6wbhbcoc4eoxaiiwrcyjsa62awhohti4lfjsxqy5vdkzgz4ya', 7, '', ''),
(20, 'bafkreidpalo45ihizqgqjqx4ff2nskgrtcprfvxtyiad2hkxpvpasri5g4', 7, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `security_question`
--

CREATE TABLE `security_question` (
  `security_code` varchar(40) NOT NULL,
  `question` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `security_question`
--

INSERT INTO `security_question` (`security_code`, `question`) VALUES
('cityBorn', 'In what city were you born?'),
('food', 'What was your favorite food as a child?'),
('petName', 'What is the name of your favorite pet?'),
('university', 'What University did you attend?'),
('yearBorn', 'What year were you born?');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(400) NOT NULL,
  `money` int(15) NOT NULL,
  `salt` varchar(22) NOT NULL,
  `security_code` varchar(40) NOT NULL,
  `sec_ans` varchar(400) NOT NULL,
  `profile_pic` blob NOT NULL,
  `email` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `money`, `salt`, `security_code`, `sec_ans`, `profile_pic`, `email`) VALUES
(3, 'testuser1234', '$2y$10$nepqWUxRqj1nECd3jpmpC.tfAT3gFAj8Mle0btTsY2YRQucfLRM4K', 5, 'nepqWUxRqj1nECd3jpmpCK', 'cityBorn', 'e4705311a7e51cc95178d0b6ff8560f1', 0x89504e470d0a1a0a0000000d4948445200000200000002000803000000c3a624c800000021504c54454c69717e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d9b2d22d90000000a74524e530017f0a5304f8a6fdbc4b08ad759000009704944415478daeddd0972ebd60e84e1cb79d8ff825f2537c99b6c59140f87c3fefe05b8540204740320fdeb1700000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000007fd3347d3f0cd3348de3344dc3d0f74de35b8988fc30754bbb7e49bb74d3200f9e1bfab19bd73798bb511a3c2ff8cbba8945123c867e6bf0ff9d04bd6faf7686ae5d77d07683efb0e6dffeaee8ff9503ea40a57d7f9ad742cc133d501bd3b21665997ca735857f5e8b334b816a6a7fbb1e42ab13d410fef1a0f0ff1684522038fc52e0febdffe0f0ff4e01dff35d873ef37a0ab3e1d02dab7fb79e46a70f4456ffff3404bef15bd1cfebc9e80377a25b2f401fc8fdf9ff55046c8902bb3f25a0fc6b03cabf36105ffeb581f8f2ff4f1b1087ab58d65bb088c425343789ffba2ea4e015f19fd7db30cb8038f9cf0c5c1cff76bd15ad0c3895e166f15fd7d672e8ccf8af374406c4d67f5d40fc6540a6ffe306c55f0664ce7f4d85c55f065c40b7de1cbbc14399d6dbe33ee04803b8560033186900580102800c2000c880c009b09930014006100064c0c10c6b55380e28cd5c5702cc2216ea0038816314605b5b02b47460ac02a403d315201d18ae00e9c0c28c6b9578a3642905b8560a1d185d009480580bc80a2a004a8002a004c417002520bc0028010598d6aab113da4b5b7702b422185d009480bd2cb52780870577d1afd5e34238d6037282fb99eb4f005be1ec0ea007eca17b4202b80d8b1d021805ec64581f81e3c0e80ea0077ccefa104432ba03e801c15320b3a0e83d807dc02e9af531380b8996004440b8042002621741164224001140021001e9128008085e045807d0805460f426c83ee833fa672580bbb06813c0066c677a5602784028da04b001db599e950036c25b699f95004e83933701b601124002648f010c0224809826cf814c822480984a00c44e82cd822580986e627c5a02380a930090009000200221012001601208090009802f700f20012440344f4b0011ddc8fcacf87b3c782b1e0c09c7a361b64176410601c6007c201798c9c39e0df364d8661ef578b08783c37d2017186e039880701bc00484ab401a307b1b6013103e0c3608fe8407dd85ba080d9f059a03668b0012207c12600a103e093005c85e075804841b412630bc07e800d93d400708ef013a40780fd001b27b800e103e0b32050adf07d803eca2facb40d780e1329004cc96812460b80c2401f752f96da86bd0ec69a0296078095000b255000550a404546b045a0520bb042800d9254001082f010a4076095000ca51e553629e072b48854b416bc092547817e00e205b07528085a9ec4951cf8396a6b2cb107720c5a96a2b680b78c030a0a226301b01643b010e207b1c6404942d0308806c19400084cb0002205b061000d9328000c8ce00f13f9c5b6f86ed80b3ad0003704a06dcf640cc11d84966f0a619d03280d11920fed91920fed91920fed91920fea767c0addce02cfed1f300feff920cb8cd4c7011ff6bb8c95ec0fcff326eb11db6ffbd90e17233d07a0220da0c90ff974bc14b854047fedd40085cd6065aedffcc9ffad8dccc0f7eebfebeffa8d8f33bff7edc3addeae7dfb76a4371b53fbf56dcfde94560e95f3b93993b2828f5ff09eff7dfeac96f90f8fefd0fc3cf2982cf75fef4eb0e45e0456c271ee158913ffed4274ef0fe2faafbc8261c3de8e92e76842f83da19149565dcb87b69c68353a07de9f0ba4d6a01ef69ff6ddbd76327832fbbfa37f3087ea0f890f7f502e6b02af0fad7ff6231450c7ef4f36f3f72617fa6c074801c9ca76673b7b2323ce0e7ffde11ce50d8142e3f84f0a771b42250f0e7ffde8faaef8a7582b6eb8fffbcd83ad6fb595e0f2572a0ed86b33e2fde2ba75b6e3177e6c03bd12ffa79b1e5ccebad415b33741f4ac2b91bde09d9fbd3276de01d36f9f837076ddb93e0cde06fbd49d306ca7e9f5be475338ccb5bbfd5761987b7ffe8d6b1931561a9f2ffd9c2a5e987b1fb2e0fdaa51b877e4ba3fe60f7a00dec56d3057e554ddf0fc3304de3d875e3384dc330f4fd6689f6e102da86b04cfbffafd67a81be6e3e3e41f124d13e37f5751f3839059a3d9b677ef0cb82ba6f68d39e5905f6ee9b5c0914917f57a540817523295846fe5d910285b6cd260265e4dfff09ac838b6b5fee938afa01f1ffe38b3db0ba0e453fa8b81790ff9f6d6f3ffcf117be3462068e89ff9f32bbb41a68c6038e8c64c0af035ff6b4941b0d34d3410f9d78b9d4b16f78e8a602bda09f0ebc33361038fa858fedae24e8a7eee8cfd78bfff18f72759f748366eace78d42c3b03ce7be1ebdc8dd3bbfbbea69fc6eeb4770f2567c0e92ffc6d976e1a5e7ce1fd3075cbe91faa17ffb3bff2795e96bf2f017e5f052ccb3c5ff6714233a0a67f077e707f6ac45f0688bf0c30ffcd256e2a2cfeff9b0159f1ef443c7a3b3c8a77f48dd020da5f117327d88bf5d7840c84eefbbf7faf26e37f0f1b00848f0318c06c33c800645b010620db0af404e04f42f0d9568000fc59089a009b093f954974a3650001902d034c80c2650001902d0308806c19600514be16b202c86e021a407613d000c29b000790dd04ec00b39b800610de043480ec26a0018437013b80ec9d802bc08f1929403a9002a40329403a9002a4032d81229928403a900564052bc59b000a50f38d300b986d051580f012a0006497000520bc042800d9254001082f010a4076095000c24b8002905d021a512b497d1b015b80f08d803560515a7700e1d47617e010a830959d06b9042c4e5dd7813c60b613e401c39d200f18ee0479c06c27c803863b416f043b848504240375003dc014d034d025482875dc851802848f027480ec1e600f7420356c84ec810ea4868d903170f638580708ef013c40b80fe001b27d802950f82cc81ee060eebe0fb0093e989bef846d820fe7de3b612630dc081a03860f038d01b387814c60b8116402c38d2009102e02cc81b3a7c1a600e193005380f0498055f029dc77256c11700af75d0788cd3918031905190319051903190519031905d18054200d48059a039a0532016c0013c00618041b06bb077c3eb7bc0b740c70220d13c00630016c806b905446092001248004a00168002e800bb8cb1cc020e8bc41d02d1f0db00b388d7bee02dc039cc64daf42f980600f601f781ef77d30a4910167c4ffc6af899201d9f1ff631cc40c1e6b006fff8fa39a4e0a1c17feae8aff1c378ccb2c0b4ac77e5ec6bafe833c0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000f7e75f17bed962b5f9676d0000000049454e44ae426082, 'leeman160561314@gmail.com'),
(7, 'leeman', '$2y$10$su5krKzL084ffh5v3sDnPe1iyrac.jynuetdTs2RRcYTpmfW0zC5e', 0, 'su5krKzL084ffh5v3sDnPn', 'cityBorn', 'c196bf6c2b3cb8e6b40bfd56240e0156', 0x89504e470d0a1a0a0000000d4948445200000200000002000803000000c3a624c800000021504c54454c69717e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d7e7d7d9b2d22d90000000a74524e530017f0a5304f8a6fdbc4b08ad759000009704944415478daeddd0972ebd60e84e1cb79d8ff825f2537c99b6c59140f87c3fefe05b8540204740320fdeb1700000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000007fd3347d3f0cd3348de3344dc3d0f74de35b8988fc30754bbb7e49bb74d3200f9e1bfab19bd73798bb511a3c2ff8cbba8945123c867e6bf0ff9d04bd6faf7686ae5d77d07683efb0e6dffeaee8ff9503ea40a57d7f9ad742cc133d501bd3b21665997ca735857f5e8b334b816a6a7fbb1e42ab13d410fef1a0f0ff1684522038fc52e0febdffe0f0ff4e01dff35d873ef37a0ab3e1d02dab7fb79e46a70f4456ffff3404bef15bd1cfebc9e80377a25b2f401fc8fdf9ff55046c8902bb3f25a0fc6b03cabf36105ffeb581f8f2ff4f1b1087ab58d65bb088c425343789ffba2ea4e015f19fd7db30cb8038f9cf0c5c1cff76bd15ad0c3895e166f15fd7d672e8ccf8af374406c4d67f5d40fc6540a6ffe306c55f0664ce7f4d85c55f065c40b7de1cbbc14399d6dbe33ee04803b8560033186900580102800c2000c880c009b09930014006100064c0c10c6b55380e28cd5c5702cc2216ea0038816314605b5b02b47460ac02a403d315201d18ae00e9c0c28c6b9578a3642905b8560a1d185d009480580bc80a2a004a8002a004c417002520bc0028010598d6aab113da4b5b7702b422185d009480bd2cb52780870577d1afd5e34238d6037282fb99eb4f005be1ec0ea007eca17b4202b80d8b1d021805ec64581f81e3c0e80ea0077ccefa104432ba03e801c15320b3a0e83d807dc02e9af531380b8996004440b8042002621741164224001140021001e9128008085e045807d0805460f426c83ee833fa672580bbb06813c0066c677a5602784028da04b001db599e950036c25b699f95004e83933701b601124002648f010c0224809826cf814c822480984a00c44e82cd822580986e627c5a02380a930090009000200221012001601208090009802f700f20012440344f4b0011ddc8fcacf87b3c782b1e0c09c7a361b64176410601c6007c201798c9c39e0df364d8661ef578b08783c37d2017186e039880701bc00484ab401a307b1b6013103e0c3608fe8407dd85ba080d9f059a03668b0012207c12600a103e093005c85e075804841b412630bc07e800d93d400708ef013a40780fd001b27b800e103e0b32050adf07d803eca2facb40d780e1329004cc96812460b80c2401f752f96da86bd0ec69a0296078095000b255000550a404546b045a0520bb042800d9254001082f010a4076095000ca51e553629e072b48854b416bc092547817e00e205b07528085a9ec4951cf8396a6b2cb107720c5a96a2b680b78c030a0a226301b01643b010e207b1c6404942d0308806c19400084cb0002205b061000d9328000c8ce00f13f9c5b6f86ed80b3ad0003704a06dcf640cc11d84966f0a619d03280d11920fed91920fed91920fed91920fea767c0addce02cfed1f300feff920cb8cd4c7011ff6bb8c95ec0fcff326eb11db6ffbd90e17233d07a0220da0c90ff974bc14b854047fedd40085cd6065aedffcc9ffad8dccc0f7eebfebeffa8d8f33bff7edc3addeae7dfb76a4371b53fbf56dcfde94560e95f3b93993b2828f5ff09eff7dfeac96f90f8fefd0fc3cf2982cf75fef4eb0e45e0456c271ee158913ffed4274ef0fe2faafbc8261c3de8e92e76842f83da19149565dcb87b69c68353a07de9f0ba4d6a01ef69ff6ddbd76327832fbbfa37f3087ea0f890f7f502e6b02af0fad7ff6231450c7ef4f36f3f72617fa6c074801c9ca76673b7b2323ce0e7ffde11ce50d8142e3f84f0a771b42250f0e7ffde8faaef8a7582b6eb8fffbcd83ad6fb595e0f2572a0ed86b33e2fde2ba75b6e3177e6c03bd12ffa79b1e5ccebad415b33741f4ac2b91bde09d9fbd3276de01d36f9f837076ddb93e0cde06fbd49d306ca7e9f5be475338ccb5bbfd5761987b7ffe8d6b1931561a9f2ffd9c2a5e987b1fb2e0fdaa51b877e4ba3fe60f7a00dec56d3057e554ddf0fc3304de3d875e3384dc330f4fd6689f6e102da86b04cfbffafd67a81be6e3e3e41f124d13e37f5751f3839059a3d9b677ef0cb82ba6f68d39e5905f6ee9b5c0914917f57a540817523295846fe5d910285b6cd260265e4dfff09ac838b6b5fee938afa01f1ffe38b3db0ba0e453fa8b81790ff9f6d6f3ffcf117be3462068e89ff9f32bbb41a68c6038e8c64c0af035ff6b4941b0d34d3410f9d78b9d4b16f78e8a602bda09f0ebc33361038fa858fedae24e8a7eee8cfd78bfff18f72759f748366eace78d42c3b03ce7be1ebdc8dd3bbfbbea69fc6eeb4770f2567c0e92ffc6d976e1a5e7ce1fd3075cbe91faa17ffb3bff2795e96bf2f017e5f052ccb3c5ff6714233a0a67f077e707f6ac45f0688bf0c30ffcd256e2a2cfeff9b0159f1ef443c7a3b3c8a77f48dd020da5f117327d88bf5d7840c84eefbbf7faf26e37f0f1b00848f0318c06c33c800645b010620db0af404e04f42f0d9568000fc59089a009b093f954974a3650001902d034c80c2650001902d0308806c19600514be16b202c86e021a407613d000c29b000790dd04ec00b39b800610de043480ec26a0018437013b80ec9d802bc08f1929403a9002a40329403a9002a4032d81229928403a900564052bc59b000a50f38d300b986d051580f012a0006497000520bc042800d9254001082f010a4076095000c24b8002905d021a512b497d1b015b80f08d803560515a7700e1d47617e010a830959d06b9042c4e5dd7813c60b613e401c39d200f18ee0479c06c27c803863b416f043b848504240375003dc014d034d025482875dc851802848f027480ec1e600f7420356c84ec810ea4868d903170f638580708ef013c40b80fe001b27d802950f82cc81ee060eebe0fb0093e989bef846d820fe7de3b612630dc081a03860f038d01b387814c60b8116402c38d2009102e02cc81b3a7c1a600e193005380f0498055f029dc77256c11700af75d0788cd3918031905190319051903190519031905d18054200d48059a039a0532016c0013c00618041b06bb077c3eb7bc0b740c70220d13c00630016c806b905446092001248004a00168002e800bb8cb1cc020e8bc41d02d1f0db00b388d7bee02dc039cc64daf42f980600f601f781ef77d30a4910167c4ffc6af899201d9f1ff631cc40c1e6b006fff8fa39a4e0a1c17feae8aff1c378ccb2c0b4ac77e5ec6bafe833c0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000f7e75f17bed962b5f9676d0000000049454e44ae426082, '21029122d@connect.polyu.hk');

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `id` int(11) NOT NULL,
  `register_verified` int(1) NOT NULL,
  `enabled_verified` int(1) NOT NULL,
  `verify_code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`id`, `register_verified`, `enabled_verified`, `verify_code`) VALUES
(3, 1, 1, '32WOQEKK4TQLK2J4'),
(7, 1, 0, '48264');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artwork`
--
ALTER TABLE `artwork`
  ADD PRIMARY KEY (`CID`),
  ADD KEY `artwork_userId_fk` (`id`);

--
-- Indexes for table `own_artwork`
--
ALTER TABLE `own_artwork`
  ADD PRIMARY KEY (`own_artwork_id`),
  ADD KEY `own_artwork_id_fk` (`id`),
  ADD KEY `own_artwork_cid_fk` (`CID`);

--
-- Indexes for table `security_question`
--
ALTER TABLE `security_question`
  ADD PRIMARY KEY (`security_code`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `security_code_fk` (`security_code`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `own_artwork`
--
ALTER TABLE `own_artwork`
  MODIFY `own_artwork_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artwork`
--
ALTER TABLE `artwork`
  ADD CONSTRAINT `artwork_userId_fk` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `own_artwork`
--
ALTER TABLE `own_artwork`
  ADD CONSTRAINT `own_artwork_cid_fk` FOREIGN KEY (`CID`) REFERENCES `artwork` (`CID`),
  ADD CONSTRAINT `own_artwork_id_fk` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `security_code_fk` FOREIGN KEY (`security_code`) REFERENCES `security_question` (`security_code`);

--
-- Constraints for table `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `verified_id_fk` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

-- -------------------------------------------------------- 
-- Database User
--
CREATE USER 'databaseadmin'@'%' IDENTIFIED BY 'databaseadmin' PASSWORD EXPIRE INTERVAL 30 DAY PASSWORD REUSE INTERVAL 90 day;
ALTER USER 'databaseadmin'@'%'  FAILED_LOGIN_ATTEMPTS 3 PASSWORD_LOCK_TIME 3;
GRANT ALL ON lamp_docker.* TO 'databaseadmin'@'%';
GRANT CREATE USER ON *.* TO 'databaseadmin'@'%';
GRANT INSERT, SELECT, UPDATE, DELETE ON lamp_docker.* TO 'databaseadmin'@'%' WITH GRANT OPTION;


CREATE USER 'csstaff'@'%'IDENTIFIED BY 'csstaff';
GRANT UPDATE ON lamp_docker.user TO 'csstaff'@'%';
GRANT SELECT, UPDATE ON lamp_docker.security_question TO 'csstaff'@'%';
GRANT SELECT, UPDATE ON lamp_docker.verification TO 'csstaff'@'%';
 
COMMIT;