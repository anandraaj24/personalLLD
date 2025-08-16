to generate question answer pdf from go pyq pdf

1. split the pdf subject wise which you want to extract for (like C Prog - whole 8.x.x), do this with https://www.drawboard.com/tools/extract-pdfs

2. then feed the subject pdf in ```pdf_link_extractor_v4.php```, it will give you a json file containing question no. and url from the go pyq pdf.

3. then feed this json file in ```pyq_pdf_generator.php```, it will download all the question and answer images from url and put in ```question_answer_images``` folder.

4. then feed the same json file in ```pyq_pdf_binder.php```, it will bind the images in a single pdf file with question no. and its url.


to generate the pdf for random questions

1. give the questions link in ```questions.json``` file

2. then feed the ```questions.json``` file in ```pdfgenerator.php```, it will download the question and answer images from the go question url and put in ```question_answer_images``` folder.

3. then run pdfbinder.php, it will bind all the images in a single pdf file with putting question link in question no.

to generate the pdf from go exam link

1. simple run ```pdfgenerator.php``` with exam url and then run ```pdfbinder.php```