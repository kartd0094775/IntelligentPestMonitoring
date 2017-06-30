#include "opencv2/core/core.hpp"
#include "opencv2/highgui/highgui.hpp"
#include "opencv2/imgproc/imgproc.hpp"
#include "iostream"
#include "cv.h"
#include <math.h>
#include <highgui.h>
#include <cxcore.h>

using namespace cv;
using namespace std;

void FillInternalContours(IplImage *pBinary, double dAreaThre)                                                                           //FindContours
{   
	char filename[100];
	FILE *pfile;
	printf("<請輸入輸出結果的檔名:>");
	scanf("%s",filename);
	strcat(filename, ".txt");
	pfile=fopen(filename,"w");
	double dConArea,ConArea;
	double TotalArea = 0 , AverageArea = 0;   
	CvSeq *pContour = NULL;   
	CvSeq *pConInner = NULL;   
	CvMemStorage *pStorage = NULL;     
	if (pBinary)   
	{    
		pStorage = cvCreateMemStorage();   
		cvFindContours(pBinary, pStorage, &pContour, sizeof(CvContour), CV_RETR_CCOMP, CV_CHAIN_APPROX_SIMPLE);     
		cvDrawContours(pBinary, pContour, CV_RGB(255, 255, 255), CV_RGB(255, 255, 255), 2, CV_FILLED, 8, cvPoint(0, 0));   
		int Coutside = 0;  
		int Cinside = 0;  
		for (; pContour != NULL; pContour = pContour->h_next)   
		{     
			for (pConInner = pContour->v_next; pConInner != NULL; pConInner = pConInner->h_next)   
			{   
				Cinside++;  
				//dConArea = fabs(cvContourArea(pConInner, CV_WHOLE_SEQ));
				//printf("%f\n", dConArea);
			}
			ConArea = fabs(cvContourArea(pContour, CV_WHOLE_SEQ));
			TotalArea = TotalArea + ConArea;
			CvRect rect = cvBoundingRect(pContour,0);
			cvRectangle(pBinary, cvPoint(rect.x, rect.y), cvPoint(rect.x + rect.width, rect.y + rect.height),CV_RGB(255,255,255), 1, 8, 0);
			if(ConArea!=0){
			Coutside++;
			printf("Insect%d :%.2f pixel\n", Coutside ,ConArea);
			fprintf(pfile,"Insect%d : %.2f pixel\n",Coutside ,ConArea);
			}
		}   
		printf("Total Insect Numbers = %d insects\n",Coutside);
		fprintf(pfile,"Total Insect Numbers = %d insects\n",Coutside);
		AverageArea = TotalArea / Coutside;
		printf("Insect Average area = %.2f pixel\n",AverageArea);
		fprintf(pfile,"Insect Average area = %.2f pixel\n",AverageArea);
		cvReleaseMemStorage(&pStorage);   
		pStorage = NULL;
		fclose(pfile);
	}  
}

int main(){
	int pos = 5;
	int cnt = 0;
	int method,insect;
	int i,j,count=0;
	CvScalar Scalar1;
	CvMemStorage *storage = cvCreateMemStorage();
	CvSeq *g_pcvSeq = NULL;
	IplConvKernel * pKernel = NULL;

	pKernel = cvCreateStructuringElementEx(pos*2+1, pos*2+1, pos, pos, CV_SHAPE_ELLIPSE,NULL);


	IplImage * img1 = cvLoadImage("Insect1.jpg");
	IplImage * img2 = cvLoadImage("Insect2.jpg");
	IplImage * img3 = cvLoadImage("Insect3.jpg");
	IplImage * img4 = cvLoadImage("Insect4.jpg");
	IplImage * imgInsectCanny1 = cvLoadImage("Insect1_L0_0.01.png");
	IplImage * imgInsectCanny2 = cvLoadImage("Insect2_L0_0.01.png");
	IplImage * imgInsectCanny3 = cvLoadImage("Insect3_L0_0.01.png");
	IplImage * imgInsectCanny4 = cvLoadImage("Insect4_L0_0.01.png");
	IplImage * imgInsectDRM1 = cvLoadImage("Insect1_red.bmp");
	IplImage * imgInsectDRM2 = cvLoadImage("Insect2_red.bmp");
	IplImage * imgInsectDRM3 = cvLoadImage("Insect3_red.bmp");
	IplImage * imgInsectDRM4 = cvLoadImage("Insect4_red.bmp");
	IplImage * imgInsectWatershed1 = cvLoadImage("Insect1Watershed.png");
	IplImage * imgInsectWatershed2 = cvLoadImage("Insect2Watershed.png");
	IplImage * imgInsectWatershed3 = cvLoadImage("Insect3Watershed.png");
	IplImage * imgInsectWatershed4 = cvLoadImage("Insect4Watershed.png");
	IplImage * Watershed = cvLoadImage("water4.png");
	IplImage * Sobelx = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
	IplImage * Sobely = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
	IplImage * Sobel = cvCreateImage(cvGetSize(img1), IPL_DEPTH_8U, 1);
	//IplImage * dst = cvCreateImage(cvSize(Gray->width,Gray->height), IPL_DEPTH_8U, 1);
	//IplImage * Sobeldst = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
	//IplImage * dilation = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * erosion = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * Opening = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * Closing = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * Result = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * ResultE = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
	//IplImage * ResultF = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);

	printf("<請選擇方法>\n1:Canny 2:DRM 3:Watershed\n");
	scanf("%d",&method);
	switch(method){
	case 1:{
			printf("<請選擇輸入圖片>\n1:Insect1 2:Insect2 3:Insect3 4:Insect4\n");
			scanf("%d",&insect);
			switch(insect){
			case 1:{
			IplImage * Gray = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect1_L0_0.01.png");
			IplImage * imgG = cvLoadImage("Insect1_L0_0.01.png");
			IplImage * imgB = cvLoadImage("Insect1_L0_0.01.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img1->height;i++){
		for(j=0;j<img1->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img1,i,j,cvScalar(255,0,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect1CannyResult.png",img1);
	cvSaveImage("Insect1CannyContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
				   }
	case 2:{
			IplImage * Gray = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect2_L0_0.01.png");
			IplImage * imgG = cvLoadImage("Insect2_L0_0.01.png");
			IplImage * imgB = cvLoadImage("Insect2_L0_0.01.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img2->height;i++){
		for(j=0;j<img2->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img2,i,j,cvScalar(255,0,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect2CannyResult.png",img2);
	cvSaveImage("Insect2CannyContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 3:{
			IplImage * Gray = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect3_L0_0.01.png");
			IplImage * imgG = cvLoadImage("Insect3_L0_0.01.png");
			IplImage * imgB = cvLoadImage("Insect3_L0_0.01.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img3->height;i++){
		for(j=0;j<img3->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img3,i,j,cvScalar(255,0,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect3CannyResult.png",img3);
	cvSaveImage("Insect3CannyContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 4:{
			IplImage * Gray = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect4_L0_0.01.png");
			IplImage * imgG = cvLoadImage("Insect4_L0_0.01.png");
			IplImage * imgB = cvLoadImage("Insect4_L0_0.01.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img4->height;i++){
		for(j=0;j<img4->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img4,i,j,cvScalar(255,0,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect4CannyResult.png",img4);
	cvSaveImage("Insect4CannyContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
			}
			break;
			}
		case 2:{
			printf("<請選擇輸入圖片>\n1:Insect1 2:Insect2 3:Insect3 4:Insect4\n");
			scanf("%d",&insect);
			switch(insect){
			case 1:{
			IplImage * Gray = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect1_red.bmp");
			IplImage * imgG = cvLoadImage("Insect1_red.bmp");
			IplImage * imgB = cvLoadImage("Insect1_red.bmp");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img1->height;i++){
		for(j=0;j<img1->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img1,i,j,cvScalar(0,0,255));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect1DRMResult.png",img1);
	cvSaveImage("Insect1DRMContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
				   }
	case 2:{
			IplImage * Gray = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect2_red.bmp");
			IplImage * imgG = cvLoadImage("Insect2_red.bmp");
			IplImage * imgB = cvLoadImage("Insect2_red.bmp");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img2->height;i++){
		for(j=0;j<img2->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img2,i,j,cvScalar(0,0,255));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect2DRMResult.png",img2);
	cvSaveImage("Insect2DRMContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 3:{
			IplImage * Gray = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect3_red.bmp");
			IplImage * imgG = cvLoadImage("Insect3_red.bmp");
			IplImage * imgB = cvLoadImage("Insect3_red.bmp");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img3->height;i++){
		for(j=0;j<img3->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img3,i,j,cvScalar(0,0,255));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect3DRMResult.png",img3);
	cvSaveImage("Insect3DRMContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 4:{
			IplImage * Gray = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect4_red.bmp");
			IplImage * imgG = cvLoadImage("Insect4_red.bmp");
			IplImage * imgB = cvLoadImage("Insect4_red.bmp");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img4->height;i++){
		for(j=0;j<img4->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img4,i,j,cvScalar(0,0,255));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect4DRMResult.png",img4);
	cvSaveImage("Insect4DRMContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
			}
		   }
		   }
		   break;
		case 3:{
			printf("<請選擇輸入圖片>\n1:Insect1 2:Insect2 3:Insect3 4:Insect4\n");
			scanf("%d",&insect);
			switch(insect){
			case 1:{
			IplImage * Gray = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img1),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect1Watershed.png");
			IplImage * imgG = cvLoadImage("Insect1Watershed.png");
			IplImage * imgB = cvLoadImage("Insect1Watershed.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img1->height;i++){
		for(j=0;j<img1->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img1,i,j,cvScalar(0,255,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect1WatershedResult.png",img1);
	cvSaveImage("/Insect1WatershedContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
				   }
	case 2:{
			IplImage * Gray = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img2),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect2Watershed.png");
			IplImage * imgG = cvLoadImage("Insect2Watershed.png");
			IplImage * imgB = cvLoadImage("Insect2Watershed.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img2->height;i++){
		for(j=0;j<img2->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img2,i,j,cvScalar(0,255,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect2WatershedResult.png",img2);
	cvSaveImage("Insect2WatershedContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 3:{
			IplImage * Gray = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img3),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect3Watershed.png");
			IplImage * imgG = cvLoadImage("Insect3Watershed.png");
			IplImage * imgB = cvLoadImage("Insect3Watershed.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img3->height;i++){
		for(j=0;j<img3->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img3,i,j,cvScalar(0,255,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect3WatershedResult.png",img3);
	cvSaveImage("Insect3WatershedContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
		   }
	case 4:{
			IplImage * Gray = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * GrayB = cvCreateImage(cvGetSize(img4),IPL_DEPTH_8U, 1);
			IplImage * imgR = cvLoadImage("Insect4Watershed.png");
			IplImage * imgG = cvLoadImage("Insect4Watershed.png");
			IplImage * imgB = cvLoadImage("Insect4Watershed.png");
			IplImage * Canny = cvCreateImage(cvGetSize(Gray),IPL_DEPTH_8U, 1);
		for(i=0;i<img4->height;i++){
		for(j=0;j<img4->width;j++){

	Scalar1 = cvGet2D(imgB, i, j);
	cvSet2D(imgB , i , j , cvScalar(Scalar1.val[0],Scalar1.val[0],Scalar1.val[0]));                                    
	cvSet2D(imgG , i , j , cvScalar(Scalar1.val[1],Scalar1.val[1],Scalar1.val[1]));
	cvSet2D(imgR , i , j , cvScalar(Scalar1.val[2],Scalar1.val[2],Scalar1.val[2]));
		}
				}
	cvCvtColor(imgB, Gray, CV_BGR2GRAY);
	cvSmooth(Gray,GrayB,CV_GAUSSIAN,5,5);
	cvCanny(GrayB , Canny , 20 , 90, 3);

	for(i=0;i<Gray->height-1;i++){                                                                                          
		for(j=0;j<Gray->width-1;j++){
			int src7 = cvGetReal2D(Canny, i, j);
			if(src7==255){
				cvSet2D(img4,i,j,cvScalar(0,255,0));
				count++;
			}
		}
	}
	FillInternalContours(Canny, 200);
	cvSaveImage("Insect4WatershedResult.png",img4);
	cvSaveImage("Insect4WatershedContour.png",Canny);
	//cvShowImage("Canny", Canny);
	break;
			}
	}
	}
	break;
	}

	
	//cvNamedWindow("Orginal");
	//cvNamedWindow("R");
	//cvNamedWindow("G");
	//cvNamedWindow("B");
	//cvNamedWindow("Gray");
	//cvNamedWindow("GrayB");
	//cvNamedWindow("Threshold");
	//cvNamedWindow("Dilation");
	//cvNamedWindow("Erosion");
	//cvNamedWindow("Closing");
	//cvNamedWindow("Sobel");
	//cvNamedWindow("Result");
	//cvNamedWindow("ResultE");
	//cvNamedWindow("Final");
	//cvNamedWindow("dst");
	//cvNamedWindow("Canny");
	//cvNamedWindow("Watershed");

	//cvShowImage("Orginal", img2);
	//cvShowImage("R", imgR);
	//cvShowImage("G", imgG);
	//cvShowImage("B", imgB);
	//cvShowImage("Gray", Gray);
	//cvShowImage("GrayB", GrayB);
	//cvShowImage("Threshold", dst);
	//cvShowImage("Dilation", Opening);
	//cvShowImage("Erosion", Closing);
	//cvShowImage("Closing", Closing);
	//cvShowImage("Sobel", Sobel);
	//cvShowImage("Result", Result);
	//cvShowImage("ResultE",ResultE);
	//cvShowImage("Final", ResultF);
	//cvShowImage("dst",dst);
	//cvShowImage("Canny", Canny);
	//cvShowImage("Watershed", Watershed);

	cvWaitKey(-1);

	cvReleaseImage(&img1);
	cvReleaseMemStorage(&storage);  
	//cvReleaseImage(&Result); 

	system("pause");
	return 0;

}
