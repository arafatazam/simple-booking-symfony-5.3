import React, { useState, useRef } from "react";
import moment from "moment";
import {
  Calendar,
  Modal,
  Spin,
  Typography,
  Form,
  Button,
  InputNumber,
  notification,
} from "antd";

const { Text } = Typography;

export default function Dashboard() {
  const [calendarBusy, setCalenderBusy] = useState(false);
  const [vacancyData, setVacancyData] = useState({});
  const [form] = Form.useForm();
  const selecTed = useRef(null);

  const [modalVisible, setModalVisible] = useState(false);
  const [modalBusy, setModalBusy] = useState(false);

  const onPanelChange = async (date, mode) => {
    if (mode == "year") {
      return;
    }
    setCalenderBusy(true);
    
    const startMoment = moment(date.format("YYYY-MM-DD")).date(1).day(0);
    const startDate = startMoment.format("YYYY-MM-DD");
    const endDate = startMoment.add(41, "days").format("YYYY-MM-DD");
    
    const api_url = import.meta.env.VITE_API_URL;
    const response = await fetch(`${api_url}/vacancy/${startDate}/${endDate}`);
    const payload = await response.json();
    const vacancyData = payload.reduce((acc, curr) => {
      acc[curr.date] = curr;
      return acc;
    }, {});
    setVacancyData(vacancyData);
    setCalenderBusy(false);
  };

  const renderDateCell = (date) => {
    let key = date.format("YYYY-MM-DD");
    if (!vacancyData[key]) {
      return <Text type="secondary">Click To Create Vacancy</Text>;
    }
    return (
      <>
        <p>
          <Text>Availabe - {vacancyData[key]["available_slots"]}</Text>
        </p>
        <p>
          <Text type="warning" mark>
            Booked - {vacancyData[key]["booked_slots"]}
          </Text>
        </p>
      </>
    );
  };

  const showModal = (date) => {
    form.resetFields();
    form.setFieldsValue({
      slotsAvailable: 12,
      price: 10.5,
    });
    setModalVisible(true);
  };

  const onDateChange = (date) => {
    let prev = selecTed.current;
    selecTed.current = date;
    if (
      prev != null &&
      (prev.year() != date.year() || prev.month() != date.month())
    ) {
      return;
    }
    showModal(date);
  };
  return (
    <>
      <Spin spinning={calendarBusy} size="large">
        <Calendar
          onPanelChange={onPanelChange}
          onSelect={onDateChange}
          dateCellRender={renderDateCell}
        ></Calendar>
      </Spin>
      <Modal
        visible={modalVisible}
        footer={null}
        onCancel={() => setModalVisible(false)}
      >
        <Spin spinning={modalBusy}>
          <Form
            form={form}
            style={{ marginTop: "30px" }}
            onFinishFailed={(errorInfo) =>
              notification.open({ type: "error", message: errorInfo })
            }
            onFinish={(values) => {
              console.log(values);
              setModalVisible(false);
            }}
          >
            <Form.Item
              name="slotsAvailable"
              rules={[{ required: true, type: "integer", min: 1 }]}
              label="Available Slots"
            >
              <InputNumber min={1} placeholder="Input number of slots" />
            </Form.Item>
            <Form.Item
              name="price"
              rules={[{ required: true, type: "float", min: 0 }]}
              label="Price"
            >
              <InputNumber placeholder="Input price" />
            </Form.Item>
            <Form.Item>
              <Button type="primary" htmlType="submit">
                Submit
              </Button>
            </Form.Item>
          </Form>
        </Spin>
      </Modal>
    </>
  );
}
